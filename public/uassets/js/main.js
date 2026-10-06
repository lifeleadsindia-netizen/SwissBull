$(document).ready(function () {
    $('#lylamount').on('input', function () {
        var amount = parseInt($(this).val());

        if (amount < 100) {
            $('#lylmsg').html('Minimum amount must be $100');
            $('#lylbtn').prop('disabled', true);
        } else if (amount % 50 != 0) {
            $('#lylmsg').html('Investment amount must be in multiple of $50');
            $('#lylbtn').prop('disabled', true);
        } else {
            $('#lylmsg').html('');
            $('#lylbtn').prop('disabled', false);
        }
    })

    $('#ufdamount').on('input', function () {
        var amount = parseInt($(this).val());

        if (amount < 200) {
            $('#ufdmsg').html('Minimum amount must be $200');
            $('#ufdbtn').prop('disabled', true);
        } else if (amount % 50 != 0) {
            $('#ufdmsg').html('Investment amount must be in multiple of $50');
            $('#ufdbtn').prop('disabled', true);
        } else {
            $('#ufdmsg').html('');
            $('#ufdbtn').prop('disabled', false);
        }
    })

    $('#vstamount').on('input', function () {
        var amount = parseInt($(this).val());

        if (amount < 300) {
            $('#vstmsg').html('Minimum amount must be $300');
            $('#vstbtn').prop('disabled', true);
        } else if (amount % 50 != 0) {
            $('#vstmsg').html('Investment amount must be in multiple of $50');
            $('#vstbtn').prop('disabled', true);
        } else {
            $('#vstmsg').html('');
            $('#vstbtn').prop('disabled', false);
        }
    })
});