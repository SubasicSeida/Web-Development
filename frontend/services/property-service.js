let PropertyService = {

    /* 

    1. init
    2. getFiltersFromHash 
    3. search 
    4. renderProperties
    5. safeDisplay
    6. toggleStar
    7. addFavorite
    8. removeFavorite
    9. getUserFavorites
    
     */

    init: function () {
        $("#search-form")[0].reset();
        window.location.hash = "properties";
        this.search();

        $("#search-form").on("submit", function (e) {
            e.preventDefault();
            PropertyService.search();
        });

        $(".sort-option").on("click", function () {
            const sort = $(this).data("sort");
            const order = $(this).data("order");
            $("#search-form input[name='sort']").val(sort);
            $("#search-form input[name='order']").val(order);
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

    search: function (page = 1) {
        const filters = this.getFiltersFromHash();
        filters.page = page;

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "properties/search",
            type: "GET",
            data: filters,
            success: (response) => {
                //console.log("Search response:", response);
                const token = localStorage.getItem("user_token");
                const payload = Utils.parseJwt(token);
                const role = payload?.user?.user_role;

                if (role === Constants.CUSTOMER_ROLE) {
                    PropertyService.getUserFavorites(function (favorites) {
                        PropertyService.renderProperties(response.data, favorites);
                    });
                } else {
                    PropertyService.renderProperties(response.data, []);
                }

                PropertyService.renderPagination(
                    "#property-pagination",
                    page,
                    response.pagination?.total_pages || 1,
                    (p) => PropertyService.search(p)
                );
            },
            error: (xhr) => toastr.error(xhr?.responseJSON?.error || "Failed to load properties.")
        });
    },

    renderProperties: function (properties, favorites = [], containerSelector = "#property-list") {
        const container = $(containerSelector);
        container.empty();

        if (!properties || properties.length === 0) {
            container.html("<p class='text-muted'>No properties found.</p>");
            return;
        }

        const token = localStorage.getItem("user_token");
        const payload = Utils.parseJwt(token);
        const role = payload?.user?.user_role;

        const showFavorite = role === Constants.CUSTOMER_ROLE;

        properties.forEach(property => {
            const isFavorited = favorites.includes(property.id);
            const starIconClass = isFavorited ? "fas" : "far";

            const starSection = showFavorite
                ? `<small class="flex-fill text-center py-2">
                        <a class="star-toggle" data-id="${property.id}">
                            <i class="${starIconClass} fa-star text-warning ms-2"></i>
                        </a>
                </small>`
                : "";

            const card = `
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="property-item rounded overflow-hidden">
                        <div class="position-relative overflow-hidden">
                            <a href="#view-listing?id=${property.id}">
                                <img class="img-fluid" src="assets/img/property-1.jpg" alt />
                            </a>
                            <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">
                                ${this.safeDisplay(property.listing_type)}
                            </div>
                            <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                ${property.property_type}
                            </div>
                        </div>
                        <div class="p-4 pb-0">
                            <h5 class="text-primary mb-3">$${property.price}</h5>
                            <a class="d-block h5 mb-2" href="#view-listing?id=${property.id}">
                                ${property.title}
                            </a>
                            <p><i class="fa fa-map-marker-alt text-primary me-2"></i>
                                ${this.safeDisplay(property.address)}, ${this.safeDisplay(property.city)}
                            </p>
                        </div>
                        <div class="d-flex border-top">
                            <small class="flex-fill text-center border-end py-2">
                                <i class="fa fa-ruler-combined text-primary me-2"></i>
                                ${this.safeDisplay(property.sqft)}
                            </small>
                            <small class="flex-fill text-center border-end py-2">
                                <i class="fa fa-bed text-primary me-2"></i>
                                ${this.safeDisplay(property.bedrooms)}
                            </small>
                            <small class="flex-fill text-center border-end py-2">
                                <i class="fa fa-bath text-primary me-2"></i>
                                ${this.safeDisplay(property.bathrooms)}
                            </small>
                            ${starSection}
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
        let icon = $(element).find('i');
        const propertyId = $(element).data('id');
        const isAdding = icon.hasClass('far');

        if (isAdding) {
            icon.removeClass('far').addClass('fas');
            this.addFavorite(propertyId, icon);
        } else {
            icon.removeClass('fas').addClass('far');
            this.removeFavorite(propertyId, icon);
        }
    },

    addFavorite: function (propertyId, icon) {
        RestClient.post(
            "favorites/" + propertyId + "/add",
            { property_id: propertyId },
            function () {
                toastr.success("Added to favorites!");
            },
            function (xhr) {
                // revert star toggle if failed
                icon.removeClass('fas').addClass('far');
                toastr.error(xhr?.responseJSON?.error || "Failed to add favorite.");
            }
        );
    },

    removeFavorite: function (propertyId, icon) {
        RestClient.delete(
            "favorites/" + propertyId + "/remove",
            { property_id: propertyId },
            function () {
                toastr.info("Removed from favorites.");
            },
            function (xhr) {
                // revert star toggle if failed
                icon.removeClass('far').addClass('fas');
                toastr.error(xhr?.responseJSON?.error || "Failed to remove favorite.");
            }
        );
    },

    getUserFavorites: function (callback) {
        const token = localStorage.getItem("user_token");
        const payload = Utils.parseJwt(token);
        const user = payload?.user;
        if (!user || user.user_role !== Constants.CUSTOMER_ROLE) {
            return callback([]);
        }

        const userId = user.id;
        if (!userId) return callback([]);

        RestClient.get(
            "favorites",
            function (response) {
                //console.log("favorites response:", response);
                // response here is all property ids returned as favorites
                callback(response);
            },
            function () {
                toastr.error("Failed to load favorites.");
                callback([])
            }
        );
    },

    getAgentProperties: function (page = 1) {
        const token = localStorage.getItem("user_token");
        const payload = Utils.parseJwt(token);
        const userId = payload?.user?.id;
        if (!userId) return toastr.error("User ID not found.");

        RestClient.get(
            `properties/agent/${userId}?page=${page}`,
            function (response) {
                const properties = response.data;
                const totalPages = response.pagination?.total_pages || 1;

                PropertyService.renderProperties(properties, [], "#agent-listings");
                PropertyService.renderPagination(
                    "#agent-pagination",
                    page,
                    totalPages,
                    (p) => PropertyService.getAgentProperties(p)
                );

            },
            function (xhr) {
                toastr.error(xhr?.responseJSON?.error || "Failed to load agent listings.");
            }
        );
    },

    getFavoriteListings: function (page = 1) {
        const token = localStorage.getItem("user_token");
        const payload = Utils.parseJwt(token);
        const userId = payload?.user?.id;
        if (!userId) return;

        RestClient.get(
            `favorites/user/${userId}?page=${page}`,
            function (response) {
                const properties = response.data;
                const totalPages = response.pagination?.total_pages || 1;

                PropertyService.renderProperties(
                    properties,
                    properties.map(p => p.id),
                    "#customer-listings"
                );

                PropertyService.renderPagination(
                    "#customer-pagination",
                    page,
                    totalPages,
                    (p) => PropertyService.getFavoriteListings(p)
                );

            },
            function () {
                toastr.error("Failed to load favorite listings.");
            }
        );
    },

    renderPagination: function (containerSelector, currentPage, totalPages, fetchFunction) {
        const container = $(containerSelector);
        container.empty();

        const prevDisabled = currentPage === 1 ? "disabled" : "";
        const nextDisabled = currentPage === totalPages ? "disabled" : "";

        const prevBtn = `
            <li class="page-item ${prevDisabled}">
            <a class="page-link mx-1" href="#" data-page="${currentPage - 1}">Previous</a>
            </li>`;
        container.append(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const active = i === currentPage ? "active" : "";
            const pageBtn = `
            <li class="page-item ${active} mx-1">
                <a class="btn btn-outline-primary page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
            container.append(pageBtn);
        }

        const nextBtn = `
            <li class="page-item ${nextDisabled}">
            <a class="page-link mx-1" href="#" data-page="${currentPage + 1}">Next</a>
            </li>`;
        container.append(nextBtn);

        container.find("a.page-link").on("click", (e) => {
            e.preventDefault();
            const page = parseInt($(e.currentTarget).data("page"));
            if (page > 0 && page <= totalPages) {
                fetchFunction.call(PropertyService, page);
            }
        });

    }

};
