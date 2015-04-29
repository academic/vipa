
##Differences between System Roles and Journal Roles
User journal roles can be seen from left panel. System roles are listed in user profile page. 


##Service Shortcut Methods 

Check if user has a role on a journal

```
$this->get('user.helper')->hasJournalRole('ROLE_JOURNAL_MANAGER');
```

in twig you can use `hasRole` teig function tı check if a user has a role for a journal

```
{% if hasRole('ROLE_EDITOR') %}
...
{%endif%}
```

or there is some shurtcut functions 
```
isSystemAdmin
isEditor
isJournalManager
```
