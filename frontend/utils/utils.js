let Utils = {
    datatable: function (table_id, columns, data, pageLength = 15) {
        if ($.fn.dataTable.isDataTable("#" + table_id)) {
            $("#" + table_id)
                .DataTable()
                .destroy();
        }
        $("#" + table_id).DataTable({
            data: data,
            columns: columns,
            pageLength: pageLength,
            lengthMenu: [2, 5, 10, 15, 25, 50, 100, "All"],
        });
    },
    parseJwt: function (token) {
        if (!token) return null;
        try {
            const payload = token.split('.')[1];
            const decoded = atob(payload);
            return JSON.parse(decoded);
        } catch (e) {
            console.error("Invalid JWT token", e);
            return null;
        }
    },

    getParameterFromHash: function (key) {
        const hash = window.location.hash.substring(1);
        const queryIndex = hash.indexOf("?");
        if (queryIndex === -1) return null;

        const queryString = hash.substring(queryIndex + 1);
        const params = new URLSearchParams(queryString);
        return params.get(key);
    }

}
