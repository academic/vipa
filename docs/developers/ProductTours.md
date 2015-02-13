#Product Tour
**"bootstrap-tour"** bower package is used for tour feature. 

We only include @apptour_css and @apptour_js in pages that will show tour. 

There is also *tour.js files for every page and this file is shown depends on user settings.

```
{% javascripts  '@OjsWorkflowBundle/Resources/public/js/tour/workflow_article_tour.js' output="c/article_workflow_tour.js" %}
    {% if app.user.setting('tour.admin.workflow.articles') == FALSE %}<script type="text/javascript" src="{{ asset_url }}"></script> {% endif %}
{% endjavascripts %} 
```

Documentation http://bootstraptour.com/api/

