var tour = new Tour({
    steps: [
        {
            element: "#article-show-details",
            title: "Show article details",
            content: "You can view all details of article this article"
        },
        {
            element: "#create-new-article",
            title: "New article",
            content: "You can create new article"
        }
    ]}
);
tour.init();
tour.start();