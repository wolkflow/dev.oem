$(function() {
    $("#email_confirm, #comMail").inputmask({
        mask: "*{1,40}[.*{1,40}][.*{1,40}][.*{1,40}]@*{1,40}[.*{2,8}][.*{1,8}]",
        greedy: false
    });
});
