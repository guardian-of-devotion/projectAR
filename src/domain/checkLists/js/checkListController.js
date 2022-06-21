// @author Regina Sharaeva
leantime.checkListController = (function () {


    //Constructor
    (function () {
        jQuery(document).ready(
            function () {

            }
        );

    })();

    //Functions

    var initAllCheckListsTable = function () {

        jQuery(document).ready(function () {

            var size = 100;

            jQuery("#allCheckLists").DataTable({
                "language": {
                    "decimal": leantime.i18n.__("datatables.decimal"),
                    "emptyTable": leantime.i18n.__("datatables.emptyTable"),
                    "info": leantime.i18n.__("datatables.info"),
                    "infoEmpty": leantime.i18n.__("datatables.infoEmpty"),
                    "infoFiltered": leantime.i18n.__("datatables.infoFiltered"),
                    "infoPostFix": leantime.i18n.__("datatables.infoPostFix"),
                    "thousands": leantime.i18n.__("datatables.thousands"),
                    "lengthMenu": leantime.i18n.__("datatables.lengthMenu"),
                    "loadingRecords": leantime.i18n.__("datatables.loadingRecords"),
                    "processing": leantime.i18n.__("datatables.processing"),
                    "search": leantime.i18n.__("datatables.search"),
                    "zeroRecords": leantime.i18n.__("datatables.zeroRecords"),
                    "paginate": {
                        "first": leantime.i18n.__("datatables.first"),
                        "last": leantime.i18n.__("datatables.last"),
                        "next": leantime.i18n.__("datatables.next"),
                        "previous": leantime.i18n.__("datatables.previous"),
                    },
                    "aria": {
                        "sortAscending": leantime.i18n.__("datatables.sortAscending"),
                        "sortDescending": leantime.i18n.__("datatables.sortDescending"),
                    }

                },
                "dom": '<"top">rt<"bottom"ilp><"clear">',
                "searching": false,
                "displayLength": 100
            });

        });
    };

    return {
        initAllCheckListsTable: initAllCheckListsTable,
    };
})();