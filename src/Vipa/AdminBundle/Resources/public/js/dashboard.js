$(document).ready(function() {
    $('#quick_switch_switch').click(function(e) {
        e.preventDefault();
        var id = $('#quick_switch_journal').val();
        window.location.href = Routing.generate('ojs_journal_dashboard_index', {'journalId': id});
    });
});
