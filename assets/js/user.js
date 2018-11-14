function selectRbacItem(itemId) {
    $("#hidden_input_user_rights").find("option[value=\"" + itemId + "\"]").prop("selected", "selected");
    updateRbacInfo();
}

function unselectRbacItem(itemId) {
    $("#hidden_input_user_rights").find("option[value=\"" + itemId + "\"]").prop("selected", false);
    updateRbacInfo();
}

function updateRbacInfo(divName) {
    divName = divName || "#rbacInForm";
    console.log(divName);

    var selectedItems = $('#tree_input_user_rights').treeview('getSelected');
    var result = '<ul>';
    selectedItems.forEach(function (item, i, arr) {
        result += '<li>' + item.text + '</li>';
    });
    result += '</ul>';
    $(divName).html(result);
}