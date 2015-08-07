$(document).ready(function() {
    $('#ojs_admin_quickswitch_switch').click(function(e) {
        e.preventDefault();
        var id = $('#ojs_admin_quickswitch_journal').val();
        var path = Routing.generate('ojs_journal_dashboard_index', {'journalId': id});
        window.location.href = path;
    });
});