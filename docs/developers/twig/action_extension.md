# Action Twig Extension

Action extension's purpose is to let developers display a set of action buttons without a hassle.

## Usage

### Button Templates
Action extension has a set of button templates available and that makes easy to place some of the common action buttons to your Twigs. Those templates are:

* back
* show
* edit
* create
* delete

Here is an example of how you can add buttons using available templates.
```
{{
    actions({
        'back': {'href': path('vipa_admin_language_index')},
        'edit': {'href': path('vipa_admin_language_edit', {'id': entity.id})},
        'delete': {'href': path('vipa_admin_language_delete', {'id': entity.id}), 'attributes': {'data-token': token}},
    })
}}
```
As you can see, when you want to display a common action button, all you need to do is to specify its path. A properly styled button with a helpful title will be generated automatically.

Notice that delete button needs a CSRF token as an attribute to work properly.

### Custom buttons

If you display a custom button, all you need to do is to add it to the array which you will pass to `action()` function. Its structure should be as following:
```
{
    ...
    'button_name': {'href': 'action_url', 'options': {'title': 'button_title', 'class': 'bootstrap_button_class', 'icon': 'fontawesome_icon'}, 'attributes': {'atrribute': 'value'}}
    ...
}
```

The extension will parse the specified parameters and then generate a button:
```
<a href="action_url" class="btn btn-sm bootstrap_button_class" title="button_title" atrribute="value"><i class="fa fontawesome_icon"></i></a>
```

### Permissions

There is an easy way to hide a button when the user lacks the permission to use it:

```
actions({
    ...

    'edit': {'href': path('vipa_admin_language_edit', {'id': entity.id}), 'permission': is_granted('DELETE', selectedJournal(), 'design')}
    ...
})
```
When `is_granted()` returns false, the button won't be displayed.
