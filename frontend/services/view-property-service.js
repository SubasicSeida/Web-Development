/* let ViewPropertyService = {
    init: function () {
        const id = Utils.getParameterFromHash("id");
        if (!id) return location.hash = "properties";
        this.propertyId = id;

        this.loadProperty();
        this.initReviewForm();
        this.initGallery();
    },

    loadProperty: function () {
        RestClient.get(`property/${this.propertyId}`, (property) => {
            this.renderDetails(property);
            this.toggleButtons(property);
            this.loadReviews();
        }, (xhr) => {
            toastr.error(xhr?.responseJSON?.error || "Failed to load property");
            location.hash = "properties";
        });
    },

    renderDetails: function (p) {
        $("h2.mb-4").text(p.title);
        $("h3.text-primary").text(`$${p.price}`);
        $('p:has(i.fa-map-marker-alt)').html(`<i class="fa fa-map-marker-alt text-primary me-2"></i>${p.address}, ${p.city}`);
        $('p:has(i.fa-ruler-combined)').html(`<i class="fa fa-ruler-combined text-primary me-2"></i>${p.sqft} Sqft`);
        $('p:has(i.fa-bed)').html(`<i class="fa fa-bed text-primary me-2"></i>${p.bedrooms} Bed`);
        $('p:has(i.fa-bath)').html(`<i class="fa fa-bath text-primary me-2"></i>${p.bathrooms} Bath`);
        $('p:contains("Property Type:")').next().text(p.property_type);
        $('p:contains("Description:")').next().text(p.description || "No description available.");

        const ul = $("ul").first().empty();
        (p.features?.length ? p.features : ["No additional features listed"]).forEach(f => ul.append(`<li>${f}</li>`));
        this.renderImages(p.images || []);
    },

    renderImages: function (imgs) {
        const row = $(".gallery-image").closest(".row").empty();
        imgs.forEach((img, i) => {
            row.append(`
                <div class="col-md-6 mb-4">
                    <img src="${img}" alt="Image ${i}" class="img-fluid rounded gallery-image"
                         data-bs-toggle="modal" data-bs-target="#galleryModal" data-index="${i}" style="cursor:pointer;" />
                </div>
            `);
        });
        this.images = imgs;
    },

    initGallery: function () {
        let index = 0;
        $(document).on("click", ".gallery-image", function () {
            index = +$(this).data("index");
            $("#modalImage").attr("src", ViewPropertyService.images[index]);
        });

        $("#prevImage").on("click", () => {
            index = (index - 1 + this.images.length) % this.images.length;
            $("#modalImage").attr("src", this.images[index]);
        });

        $("#nextImage").on("click", () => {
            index = (index + 1) % this.images.length;
            $("#modalImage").attr("src", this.images[index]);
        });
    },

    toggleButtons: function (p) {
        const token = localStorage.getItem("user_token");
        const user = token ? Utils.parseJwt(token)?.user : null;

        const isCustomer = user?.user_role === Constants.CUSTOMER_ROLE;
        const isAgent = user?.user_role === Constants.AGENT_ROLE && user?.id === p.agent_id;

        $(`[data-bs-target="#buyModal"]`).toggle(isCustomer && p.listing_type === "For Sale");
        $(`[data-bs-target="#rentModal"]`).toggle(isCustomer && p.listing_type === "For Rent");
        $(`[data-bs-target="#deleteModal"]`).toggle(isAgent);

        $("#confirmDeleteBtn").off("click").on("click", () => this.deleteProperty(p.id));
    },

    deleteProperty: function (id) {
        RestClient.delete(`properties/${id}`, null, () => {
            toastr.success("Deleted successfully");
            location.hash = "properties";
        }, (xhr) => {
            toastr.error(xhr?.responseJSON?.error || "Deletion failed");
        });
    },

    loadReviews: function () {
        RestClient.get(`reviews/property/${this.propertyId}`, (res) => this.renderReviews(res));
    },

    renderReviews: function (reviews) {
        const section = $(".reviews-section").empty();
        if (!reviews?.length) return section.html("<p>No reviews yet.</p>");

        const avg = (reviews.reduce((a, b) => a + b.rating, 0) / reviews.length).toFixed(1);
        $("h2.mb-0").html(`<i class="fas fa-star text-warning"></i>${avg}`);
        $("small.text-muted").text(`${reviews.length} reviews`);

        reviews.forEach(r => {
            const stars = Array(5).fill(0).map((_, i) =>
                i < r.rating ? `<i class="fas fa-star"></i>` : `<i class="far fa-star"></i>`).join("");
            section.append(`
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">${r.user_name}</h5>
                        <div class="text-warning">${stars}</div>
                        <p class="card-text">${r.comment}</p>
                        <small class="text-muted">Posted on ${new Date(r.created_at).toLocaleDateString()}</small>
                    </div>
                </div>
            `);
        });
    },

    initReviewForm: function () {
        $("#reviewForm").on("submit", (e) => {
            e.preventDefault();
            const rating = $(".star-rating.fas").length;
            const comment = $("#comment").val();
            if (!rating) return toastr.warning("Select a rating");

            RestClient.post("reviews", {
                property_id: this.propertyId,
                rating,
                comment
            }, () => {
                toastr.success("Review submitted");
                $("#reviewForm")[0].reset();
                $(".star-rating").removeClass("fas").addClass("far");
                this.loadReviews();
            }, (xhr) => toastr.error(xhr?.responseJSON?.error || "Review submission failed"));
        });

        $(".star-rating").on("click", function () {
            const val = +$(this).data("value");
            $(".star-rating").each(function () {
                $(this).toggleClass("fas", $(this).data("value") <= val)
                    .toggleClass("far", $(this).data("value") > val);
            });
        });
    }
};
 */