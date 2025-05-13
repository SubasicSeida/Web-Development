flatpickr("#availabilityCalendar", {
    mode: "range",
    dateFormat: "Y-m-d",
    minDate: "today",
    disable: [
        {
            from: "2025-04-01",
            to: "2025-05-01"
        },
        {
            from: "2025-09-01",
            to: "2025-12-01"
        },
        "2025-04-15",
        "2025-04-20"
    ]
});