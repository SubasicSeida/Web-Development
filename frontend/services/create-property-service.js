let CreatePropertyService = {

    /* 

    1. addFeature
    2. removeFeature
    3. renderFeatures
    4. handleImageUpload
    5. handleImageRemove

     */

    features: [],
    imgUrls: [],

    addFeature: function () {
        const featureText = $("#newFeatureInput").val().trim();
        if (featureText.length) {
            this.features.push(featureText);
            this.renderFeatures();
            $("#newFeatureInput").val('');
        }
    },

    removeFeature: function (e) {
        const button = e.target.closest(".remove-feature");
        if (button) {
            const index = button.dataset.index;
            this.features.splice(index, 1);
            this.renderFeatures();
        }
    },

    renderFeatures: function () {
        const featureContainer = $("#featureContainer");
        if (!featureContainer.length) return;

        featureContainer.html(this.features.map((feature, index) => `
            <div class="d-flex align-items-center mb-2">
                <span class="me-2"><i class="fas fa-check-circle text-primary"></i></span>
                <span class="flex-grow-1">${feature}</span>
                <button type="button" class="btn btn-sm btn-outline-danger remove-feature" data-index="${index}"
                onclick="CreatePropertyService.removeFeature(event)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `).join(''));

        $('#featuresJson').val(JSON.stringify(this.features));
    },

    handleImageUpload: function (e) {
        const files = e.target.files;
        if (!files || !files.length) return;

        if (files.length > 15) {
            alert('You cannot upload more than 15 images.');
            e.target.value = '';
            return;
        }

        const previewContainer = $("#imagePreview");
        this.imageFiles = this.imageFiles || [];

        Array.from(files).forEach(file => {
            if (!file.type.match('image.*')) return;

            this.imageFiles.push(file);

            const reader = new FileReader();

            reader.onloadend = () => {
                const previewDiv = $('<div>').addClass('position-relative')
                    .css({ width: '120px', height: '120px' });

                previewDiv.html(`
                    <img src="${reader.result}" class="img-thumbnail h-100 w-100 object-fit-cover">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 remove-image">
                        <i class="fas fa-times"></i>
                    </button>
                `);

                previewContainer.append(previewDiv);
            };

            reader.readAsDataURL(file);
        });

        previewContainer.off('click', '.remove-image').on('click', '.remove-image', function (e) {
            e.preventDefault();
            const index = $(this).closest('.position-relative').index();
            $(this).closest('.position-relative').remove();

            if (CreatePropertyService.imageFiles && CreatePropertyService.imageFiles.length > index) {
                CreatePropertyService.imageFiles.splice(index, 1);
            }
        });
    },

    uploadImagesToImgBB: async function () {
        const uploadedUrls = [];

        for (const file of this.imageFiles || []) {
            const base64 = await this.readFileAsBase64(file);
            const base64Image = base64.split(',')[1];

            try {
                const response = await fetch(`https://api.imgbb.com/1/upload?key=${Constants.IMGBB_API_KEY}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `image=${encodeURIComponent(base64Image)}`
                });

                const data = await response.json();
                if (data?.data?.url) {
                    uploadedUrls.push(data.data.url);
                } else {
                    console.error("ImgBB upload failed:", data);
                }
            } catch (err) {
                console.error("Upload error:", err);
            }
        }

        return uploadedUrls;
    },

    readFileAsBase64: function (file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    },

    submitPropertyData: async function () {
        const form = $("form")[0];
        const submitBtn = $("#submitPropertyBtn");

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        submitBtn.prop("disabled", true);
        $.blockUI({ message: '<h4>Uploading... Please wait.</h4>' });

        try {
            const propertyData = {
                title: $("#listingTitle").val().trim(),
                description: $("#listingDescription").val().trim(),
                price: parseFloat($("#listingPrice").val()),
                property_type: $("#propertyType").val(),
                listing_type: $("#listingType").val(),
                sqft: parseInt($("#sqft").val()),
                bedrooms: parseInt($("#bedrooms").val()),
                bathrooms: parseInt($("#bathrooms").val()),
                address: $("#address").val().trim(),
                city: $("#city").val().trim(),
                zip_code: $("#zipCode").val().trim(),
                country: $("#country").val().trim(),
                additional_features: JSON.stringify(this.features)
            };

            RestClient.post(
                "property",
                propertyData,
                async (createdProperty) => {
                    const propertyId = createdProperty;
                    if (!propertyId) {
                        toastr.error("Property created but ID missing.");
                        submitBtn.prop("disabled", false);
                        $.unblockUI();
                        return;
                    }

                    const imageUrls = await this.uploadImagesToImgBB();

                    if (imageUrls.length) {
                        RestClient.post(
                            `property/${propertyId}/uploadImages`,
                            { images: imageUrls },
                            () => {
                                toastr.success("Property and images uploaded successfully.");
                                window.location.href = "index.html";
                            },
                            (xhr) => {
                                toastr.error("Property created, but image upload failed.");
                                console.error(xhr.responseText);
                                submitBtn.prop("disabled", false);
                                $.unblockUI();
                            }
                        );
                    } else {
                        toastr.success("Property created successfully (no images uploaded).");
                        window.location.href = "index.html";
                    }
                },
                (xhr) => {
                    toastr.error("Failed to create property.");
                    console.error(xhr.responseText);
                    submitBtn.prop("disabled", false);
                    $.unblockUI();
                }
            );

        } catch (err) {
            console.error("error:", err);
            toastr.error("Unexpected error occurred.");
            submitBtn.prop("disabled", false);
            $.unblockUI();
        }
    }
}