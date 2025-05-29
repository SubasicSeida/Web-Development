var PropertyService = {

    /* getFiltersFromHash: function () {
        const hash = window.location.hash;
        const query = hash.split("?")[1];
        return query ? Object.fromEntries(new URLSearchParams(query).entries()) : {};
    },

    search: function () {
        const filters = this.getFiltersFromHash();
        const query = $.param(filters);

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "properties/search?" + query,
            type: "GET",
            success: (response) => {
                console.log("Search response:", response);
                this.renderProperties(response.data)
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
                                <a href="#view-listing"><img class="img-fluid" src="assets/img/property-1.jpg"
                                        alt /></a>
                                <div
                                    class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">
                                    For Sell
                                </div>
                                <div
                                    class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                    Appartment
                                </div>
                            </div>
                            <div class="p-4 pb-0">
                                <h5 class="text-primary mb-3">$12,345</h5>
                                <a class="d-block h5 mb-2" href="#view-listing">Golden Urban House For Sell</a>
                                <p>
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>123
                                    Street, New York, USA
                                </p>
                            </div>
                            <div class="d-flex border-top">
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-ruler-combined text-primary me-2"></i>1000
                                    Sqft</small>
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-bed text-primary me-2"></i>3
                                    Bed</small>
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-bath text-primary me-2"></i>2
                                    Bath</small>
                                <small class="flex-fill text-center py-2"><a class="star-toggle"><i
                                            class="far fa-star text-warning ms-2"></i></a></small>
                            </div>
                        </div>
                    </div>
            `;
            container.append(card);
        });
    } */
};
