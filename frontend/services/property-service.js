var PropertyService = {

    init: function () {
        $("#search-form")[0].reset();
        window.location.hash = "properties";
        this.search();

        $("#search-form").on("submit", function (e) {
            e.preventDefault();
            PropertyService.search();
        });
    },

    getFiltersFromHash: function () {
        const formData = $("#search-form").serializeArray();
        const filters = {};

        formData.forEach(item => {
            if (item.value) {
                filters[item.name] = item.value;
            }
        });

        return filters;
    },

    search: function () {
        const filters = this.getFiltersFromHash();

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "properties/search",
            type: "GET",
            data: filters,
            success: (response) => {
                console.log("Search response:", response);
                this.renderProperties(response.data);
            },
            error: (xhr) => toastr.error(xhr?.responseJSON?.error || "Failed to load properties.")
        });
    },

    renderProperties: function (properties) {
        const container = $("#property-list");
        container.empty();

        if (!properties || properties.length === 0) {
            container.html("<p class='text-muted'>No properties found.</p>");
            return;
        }

        properties.forEach(property => {
            const card = `
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="property-item rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <a href=#view-listing?id=${property.id}"><img class="img-fluid" src="assets/img/property-1.jpg"
                                        alt /></a>
                                <div
                                    class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">
                                    ${this.safeDisplay(property.listing_type)}
                                </div>
                                <div
                                    class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                    ${property.property_type}
                                </div>
                            </div>
                            <div class="p-4 pb-0">
                                <h5 class="text-primary mb-3">$${property.price}</h5>
                                <a class="d-block h5 mb-2" href="#view-listing?id=${property.id}">
                                    ${property.title}
                                </a>
                                <p>
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    ${this.safeDisplay(property.address)}, ${this.safeDisplay(property.city)}
                                </p>
                            </div>
                            <div class="d-flex border-top">
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-ruler-combined text-primary me-2"></i>
                                        ${this.safeDisplay(property.sqft)}
                                </small>
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-bed text-primary me-2"></i>
                                        ${this.safeDisplay(property.bedrooms)}
                                </small>
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-bath text-primary me-2"></i>
                                        ${this.safeDisplay(property.bathrooms)}
                                </small>
                                <small class="flex-fill text-center py-2"><a class="star-toggle" data-id="${property.id}"
                                onClick="PropertyService.toggleStar(this)">
                                    <i class="far fa-star text-warning ms-2"></i></a></small>
                            </div>
                        </div>
                    </div>
            `;
            container.append(card);
        });
    },

    safeDisplay: function (value, fallback = 'N/A') {
        return value ?? fallback;
    },

    toggleStar: function (element) {
        const token = localStorage.getItem("user_token");

        let icon = $(this).find('i');
        const propertyId = $(element).data('id');
        const isFavorite = icon.hasClass('far');

        icon.toggleClass('far').toggleClass('fas');

        if (isFavorite) {
            this.addFavorite(propertyId);
        } else {
            this.removeFavorite(propertyId);
        }
    },

    addFavorite: function (propertyId) {
        RestClient.post(
            "favorites/" + propertyId + "/add",
            { property_id: propertyId },
            function () {
                toastr.success("Added to favorites!");
            },
            function (xhr) {
                icon?.toggleClass('far').toggleClass('fas');
                toastr.error(xhr?.responseJSON?.error || "Failed to add favorite.");
            }
        );
    },

    removeFavorite: function (propertyId) {
        RestClient.delete(
            "favorites/" + propertyId + "/remove",
            { property_id: propertyId },
            function () {
                toastr.info("Removed from favorites.");
            },
            function (xhr) {
                icon?.toggleClass('far').toggleClass('fas');
                toastr.error(xhr?.responseJSON?.error || "Failed to remove favorite.");
            }
        );
    }
};
