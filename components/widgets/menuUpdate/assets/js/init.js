
function initTrees() {
    var widgets = $(".xtree");
    var arr =[];
    widgets.each(function () {
        var treeName = this.id;
        window[treeName] = Object.create(MENU_TREE);
        var qq = window[treeName];
        arr.push(qq.init(treeName));
    });
    collector.run(arr);

}
