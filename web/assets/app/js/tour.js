var tour = new Tour({
    steps: [
        {
            element: "#link-admin-all-journals",
            title: "All journals",
            content: "List all journals"
        },
        {
            element: "#link-admin-my-journals",
            title: "My journals",
            content: "You can see journals that you've any access."
        }
    ]}
);
tour.init();
tour.start();