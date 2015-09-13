$(document).ready(function() {
    $('#ojs_admin_quickswitch_switch').click(function(e) {
        e.preventDefault();
        var id = $('#ojs_admin_quickswitch_journal').val();
        window.location.href = Routing.generate('ojs_journal_dashboard_index', {'journalId': id});
    });
});
