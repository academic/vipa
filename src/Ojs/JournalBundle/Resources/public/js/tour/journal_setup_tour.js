var tour = new Tour({
        steps: [
            {
                element: "#journal-setup-steps",
                title: "Journal Setup Steps",
                content: "You can watch your setup proccess",
                placement: "bottom"
            }
        ]}
);
tour.init();
tour.start();