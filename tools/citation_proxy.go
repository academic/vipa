package main

import (
    "net/http"
    "log"
    sh "github.com/codeskyblue/go-sh"
)

func handler(w http.ResponseWriter, r *http.Request) {
    q := r.FormValue("q")
    if len(q) < 0 {
        log.Fatal("q?")
    }

    out, err := sh.Command("./citationParseBulk","\""+q+"\"").Output()
    if err != nil {
        log.Fatal(err)
    }

    w.Write(out)
}

func main() {
    http.HandleFunc("/", handler)
    http.ListenAndServe(":8090", nil)
}