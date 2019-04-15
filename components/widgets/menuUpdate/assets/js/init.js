
function initTrees() {
    var elems = document.getElementsByName('_params_*');
    console.log(elems);

    var widgets = $(".xtree");
    var arr =[];
    widgets.each(function () {
      //  console.log(this.id);
        var treeName = this.id;
        window[treeName] = Object.create(MENU_TREE);
      //  console.log(window[treeName]);
        var qq = window[treeName];
      //  console.log(qq);
        arr.push(qq.init(treeName, '_params_' + treeName));

    });
    collector.run(arr);

}
