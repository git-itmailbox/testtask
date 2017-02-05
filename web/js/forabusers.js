
        $(document).ready(function () {
            $("#showReport").on("click",showReport);

        });

    function showReport() {
        var monthId = $("#month").val();
        $('#tableReport').find("tbody").find("tr").empty();
        $.ajax({
            url: 'abusers/companies/'+monthId,
            dataType: 'JSON',
            success: function(data){

                // formatData=[];
                data.map(function (row) {

                    row.name = row.url;
                   row.summary /= Math.pow(1024,4); //convert to TB
                   row.summary = Math.round(row.summary*10)/10 + ' TB'; //round to 0,1

                });
                // var dt = JSON.stringify({records: formatData});
                // data.records=formatData;
                $('#tableReport').dynatable({
                    dataset: {
                        records: data
                    },
                    features: {
                        paginate: false,
                        search: false,
                        recordCount: false,
                        perPageSelect: false
                    }
                });
            }
        });

    }

