$(document).ready(function () {
    $("#showReport").on("click", showReport);
    $("#sbmtFormUsers").on("click", function () {
            var isEdit = $("#isedit").val();
            (parseInt(isEdit) > 0) ? editUser() : addUser();
        }
    );
    $("#sbmtFormCompanies").on("click", function () {
            var isEdit = $("#isedit").val();
            (parseInt(isEdit) > 0) ? editCompany() : addCompany();
        }
    );
    $(document).on("click",".dltbtn", function () {
	
        deleteUser(this);
        console.log('deleted');
	return false;
    });
    $(document).on("click", ".dltCompanyBtn", function () {

        deleteCompany(this);
	return false;
    });
    $(document).on("click",".editbtn", function () {
	
        fillUserData(this);
	return false;
    });

    $(document).on("click", ".editcompany", function () {

        fillCompanyData(this);
	return false;
    });

});

function deleteCompany(e) {
    var idCmp = $(e).data("id");
    $.post(
        "/companies/delete",
        {id: idCmp},
        function (data) {
            if (parseInt(idCmp) == parseInt(data.id)) {
                //update view
                console.log('Company has been deleted');
                $(e).closest('tr').fadeOut(300, function () {
                    $(this).remove();
                })
            }
        },
        'json'
    );
}

function deleteUser(e) {
    var idUser = $(e).data("id");
    $.post(
        "/users/delete",
        {id: idUser},
        function (data) {
            if (parseInt(idUser) == parseInt(data.id)) {
                //update view
                console.log('User has been deleted');
                $(e).closest('tr').fadeOut(300, function () {
                    $(this).remove();
                });
            }
        },
        'json'
    ).then($.pjax.reload({container: '#pjaxupd'}));
}

function fillUserData(e) {
    var isEdit = $("#isedit");
    var idUser = $(e).data("id");
    if (idUser > 0) {
        isEdit.val(idUser);
        $(".modal-title")[0].innerText = "Edit User";
        var name = $("#name");
        var email = $("#email");
        var company = $("#company_id");
        $.post("/users/getuser", {id: idUser}, function (data) {
            data = data.data
            console.log(data.data);
            company.val(data['company_id']);
            name.val(data['name']);
            email.val(data['email']);

        }, 'json');
    }
    else {
        isEdit.val(0);
        resetMyModal();
        $(".modal-title")[0].innerText = "Add new User";
    }
    return;

}
function fillCompanyData(e) {
    var isEdit = $("#isedit");
    var idCmp = $(e).data("id");
    var curRow = $(e).closest('tr');
    // var idCmp = $(curRow).data("id");

	console.log(e);
    if (idCmp > 0) {
        isEdit.val(idCmp);
        $(".modal-title")[0].innerText = "Edit Company";
        var name = $("#name");
        var quota = $("#quota");
        $.post("/companies/getcompany", {id: idCmp}, function (data) {
            data = data.data
            console.log(data.data);
            name.val(data['name']);
            quota.val(data['quota']);

        }, 'json');
    }
    else {
        isEdit.val(0);
        // resetMyModal();
        $(".modal-title")[0].innerText = "Add new Company";
    }
    return;

}

function editUser() {
    var isEdit = $("#isedit");
    var idUser = $(isEdit).val();
    var curRow = $("#tableUsers").find("table").find("tr[data-key='" + idUser + "']")[0];

    var name = $("#name");
    var email = $("#email");
    var company = $("#company_id");

    $.post(
        "/users/update",
        {//send form
            id: idUser,
            name: name.val(),
            email: email.val(),
            company_id: company.val(),
        },
        function (data) {
	console.log(data);
            if ((parseInt(idUser) == parseInt(data.data.id)) && data.message === undefined) {
                //update view
                console.log('User has been updated');
                $(curRow).children()[0].innerHTML = data.data.name;
                $(curRow).children()[1].innerHTML = data.data.email;
                $(curRow).children()[2].innerHTML = $(company).find("option:selected").text();
                $("#myModal").modal('hide');

            }
            else {

		console.log('smth wrong' + data.data.id);
		showError(data);
		}
        },
        'json'
    );
}

function showError(data) {

for(var key in data.message ){
			alert(data.message[key][0]);
			
			}
}

function resetMyModal() {
    const n = $("#name"),
        e = $("#email"),
        c = $("#company_id");

    n.val("");
    e.val("");
    c.val("");
}

function addUser() {

    var name = $("#name"),
        email = $("#email"),
        company = $("#company_id");
    $.post(
        "/users/create",
        {
            name: name.val(),
            email: email.val(),
            company_id: company.val(),
        },
        function (data) {
	if(data.message === undefined){
            $("#myModal").modal('hide');
            $.pjax.reload({container: '#pjaxupd'});
	}else{
 	showError(data);
	}
        }, "json"
    );
}

function addCompany() {

    var name = $("#name"),
        quota = $("#quota");

    $.post(
        "/companies/create",
        {
            name: name.val(),
            quota: quota.val(),
        },
        function (data) {
            $("#myModal").modal('hide');
            if(data.message) alert(data.message);
            setTimeout(function() { $.pjax.reload({container: '#pjaxupd'}); }, 1000);
        }, "json"
    );
}


function editCompany() {
    var isEdit = $("#isedit");
    var idCompany = $(isEdit).val();
    var name = $("#name");
    var quota = $("#quota");

    $.post(
        "/companies/update",
        {//send form
            id: idCompany,
            name: name.val(),
            quota: quota.val(),
        },
        function (data) {
            if (parseInt(idCompany) == parseInt(data.data.id) && data.message === undefined) {
                //update view
                console.log('User has been updated');
                $("#myModal").modal('hide');
                $.pjax.reload({container: '#pjaxupd'})
            }
            else showError(data);
        },
        'json'
    );
}

function showReport() {
    var monthId = $("#month").val();
    $.ajax({
        url: '/abusers/companies/' + monthId,
        dataType: 'json',
        success: function (data) {
            data.map(function (row) {
                row.name = row.url;
                row.summary /= Math.pow(1024, 4); //convert to TB
                row.summary = Math.round(row.summary * 10) / 10 + ' TB'; //round to 0,1
            });
            var dynatable = $('#tableReport').dynatable({
                    dataset: {records: data},
                    features: {
                        pushState: false,
                        paginate: false,
                        search: false,
                    }
                },
                {}).data("dynatable");
            dynatable.settings.dataset.originalRecords = data;
            dynatable.process();
        }
    });

}
