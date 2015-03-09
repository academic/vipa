var tour = new Tour({
    backdrop: true,
    keyboard:false,
    steps: [
        {
            element: "#workflow_days",
            title: "Days remaining",
            content: "This label indicates that the number of remaining or overdue days.",
            placement:"left"
        },
        {
            element: "#article_search_google",
            title: "Search on google",
            content: "This action will search article's title on google scholar"
        },
        {
            element: "#article_search_wiki",
            title: "Search on Wikipedia",
            content: "This action will search article's title on Wikipedia (en)"
        },
        {
            element: "#article_search_pubmed",
            title: "Search on Pubmed",
            content: "This action will search article's title on Pubmed"
        },
        {
            element: "#article_workflow_someone_working",
            title: "Someone is working on this step",
            content: "This label indicates that someone is working on this step. You  can see the username depens on the workflow step settings.",
            placement:"bottom"
        }
        
    ]}
);
tour.init();
tour.start();