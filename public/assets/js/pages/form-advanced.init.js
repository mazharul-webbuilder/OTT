!(function (s) {
    "use strict";
    function e() {}
    (e.prototype.init = function () {
        s(".select2").select2(),
           
            s(".select2-limiting").select2({ maximumSelectionLength: 2 }),
            s(".select2-search-disable").select2({
                minimumResultsForSearch: 1 / 0,
            }),
            s(".select2-ajax").select2({
                ajax: {
                    url: "https://api.github.com/search/repositories",
                    dataType: "json",
                    delay: 250,
                    data: function (e) {
                        return { q: e.term, page: e.page };
                    },
                    processResults: function (e, t) {
                        return (
                            (t.page = t.page || 1),
                            {
                                results: e.items,
                                pagination: {
                                    more: 30 * t.page < e.total_count,
                                },
                            }
                        );
                    },
                    cache: !0,
                },
                placeholder: "Search for a repository",
                minimumInputLength: 1,
                templateResult: function (e) {
                    if (e.loading) return e.text;
                    var t = s(
                        "<div class='select2-result-repository clearfix'><div class='select2-result-repository__avatar'><img src='" +
                            e.owner.avatar_url +
                            "' /></div><div class='select2-result-repository__meta'><div class='select2-result-repository__title'></div><div class='select2-result-repository__description'></div><div class='select2-result-repository__statistics'><div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div><div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div><div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div></div></div></div>"
                    );
                    return (
                        t
                            .find(".select2-result-repository__title")
                            .text(e.full_name),
                        t
                            .find(".select2-result-repository__description")
                            .text(e.description),
                        t
                            .find(".select2-result-repository__forks")
                            .append(e.forks_count + " Forks"),
                        t
                            .find(".select2-result-repository__stargazers")
                            .append(e.stargazers_count + " Stars"),
                        t
                            .find(".select2-result-repository__watchers")
                            .append(e.watchers_count + " Watchers"),
                        t
                    );
                },
                templateSelection: function (e) {
                    return e.full_name || e.text;
                },
            }),
            s(".select2-templating").select2({
                templateResult: function (e) {
                    return e.id
                        ? s(
                              '<span><img src="assets/images/flags/select2/' +
                                  e.element.value.toLowerCase() +
                                  '.png" class="img-flag" /> ' +
                                  e.text +
                                  "</span>"
                          )
                        : e.text;
                },
            }),
            
            // s("#timepicker").timepicker({
            //     icons: {
            //         up: "mdi mdi-chevron-up",
            //         down: "mdi mdi-chevron-down",
            //     },
            //     appendWidgetTo: "#timepicker-input-group1",
            // }),
            // s("#timepicker2").timepicker({
            //     showMeridian: !1,
            //     icons: {
            //         up: "mdi mdi-chevron-up",
            //         down: "mdi mdi-chevron-down",
            //     },
            //     appendWidgetTo: "#timepicker-input-group2",
            // }),
            // s("#timepicker3").timepicker({
            //     minuteStep: 15,
            //     icons: {
            //         up: "mdi mdi-chevron-up",
            //         down: "mdi mdi-chevron-down",
            //     },
            //     appendWidgetTo: "#timepicker-input-group3",
            // });
         
        s('[data-toggle="touchspin"]').each(function (e, t) {
            var a = s.extend({}, i, s(t).data());
            s(t).TouchSpin(a);
        }) 
    }),
        (s.AdvancedForm = new e()),
        (s.AdvancedForm.Constructor = e);
})(window.jQuery),
    (function () {
        "use strict";
        window.jQuery.AdvancedForm.init();
    })()
    