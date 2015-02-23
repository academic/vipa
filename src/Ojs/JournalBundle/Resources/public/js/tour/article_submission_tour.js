var tour = new Tour({
    steps: [
        {
            element: "#article-submission-steps",
            title: "Submission steps",
            content: "You can watch your article submission proccess",
            placement: "bottom"
        }
    ]}
);
tour.init();
tour.start();