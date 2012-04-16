var NAVTREE =
[
  [ "Swadhin Framework", "index.html", [
    [ "Data Structures", "annotated.html", [
      [ "Blocks", "class_blocks.html", null ],
      [ "Bootstrap", "class_bootstrap.html", null ],
      [ "Config", "class_config.html", null ],
      [ "Core", "class_core.html", null ],
      [ "CoreController", "class_core_controller.html", null ],
      [ "CoreException", "class_core_exception.html", null ],
      [ "CoreForm", "class_core_form.html", null ],
      [ "CoreModel", "class_core_model.html", null ],
      [ "CoreValidator", "class_core_validator.html", null ],
      [ "CoreView", "class_core_view.html", null ],
      [ "DbException", "class_db_exception.html", null ],
      [ "DbGeneric", "class_db_generic.html", null ],
      [ "DbMysql", "class_db_mysql.html", null ],
      [ "DbMysql_query_builder", "class_db_mysql__query__builder.html", null ],
      [ "DbPdo", "class_db_pdo.html", null ],
      [ "DbPdo_mysql", "class_db_pdo__mysql.html", null ],
      [ "Error", "class_error.html", null ],
      [ "Funcs", "class_funcs.html", null ],
      [ "Html", "class_html.html", null ],
      [ "LibAuthentication", "class_lib_authentication.html", null ],
      [ "LibDebug", "class_lib_debug.html", null ],
      [ "LibFiles", "class_lib_files.html", null ],
      [ "LibForm", "class_lib_form.html", null ],
      [ "LibUrl", "class_lib_url.html", null ],
      [ "Swadhin", "class_swadhin.html", null ],
      [ "Template", "class_template.html", null ],
      [ "User_config", "class_user__config.html", null ],
      [ "Validator", "class_validator.html", null ]
    ] ],
    [ "Data Structure Index", "classes.html", null ],
    [ "Class Hierarchy", "hierarchy.html", [
      [ "Bootstrap", "class_bootstrap.html", null ],
      [ "Config", "class_config.html", null ],
      [ "Core", "class_core.html", null ],
      [ "CoreController", "class_core_controller.html", null ],
      [ "CoreException", "class_core_exception.html", null ],
      [ "CoreForm", "class_core_form.html", null ],
      [ "CoreModel", "class_core_model.html", null ],
      [ "CoreValidator", "class_core_validator.html", [
        [ "Validator", "class_validator.html", null ]
      ] ],
      [ "CoreView", "class_core_view.html", [
        [ "Template", "class_template.html", null ],
        [ "Template", "class_template.html", null ]
      ] ],
      [ "DbException", "class_db_exception.html", null ],
      [ "DbGeneric", "class_db_generic.html", [
        [ "DbMysql_query_builder", "class_db_mysql__query__builder.html", [
          [ "DbMysql", "class_db_mysql.html", null ],
          [ "DbPdo_mysql", "class_db_pdo__mysql.html", null ]
        ] ],
        [ "DbPdo", "class_db_pdo.html", null ]
      ] ],
      [ "Error", "class_error.html", null ],
      [ "Funcs", "class_funcs.html", null ],
      [ "Html", "class_html.html", [
        [ "Blocks", "class_blocks.html", null ]
      ] ],
      [ "LibAuthentication", "class_lib_authentication.html", null ],
      [ "LibDebug", "class_lib_debug.html", null ],
      [ "LibFiles", "class_lib_files.html", null ],
      [ "LibForm", "class_lib_form.html", null ],
      [ "LibUrl", "class_lib_url.html", null ],
      [ "Swadhin", "class_swadhin.html", null ],
      [ "User_config", "class_user__config.html", null ]
    ] ],
    [ "Data Fields", "functions.html", null ],
    [ "File List", "files.html", [
      [ "bootstrap.php", null, null ],
      [ "config.php", null, null ],
      [ "index.php", null, null ],
      [ "userconfig.php", null, null ],
      [ "core/blocks.php", null, null ],
      [ "core/core.php", null, null ],
      [ "core/error.php", null, null ],
      [ "core/funcs.php", null, null ],
      [ "core/html.php", null, null ],
      [ "core/swadhin.php", null, null ],
      [ "core/validator.php", null, null ],
      [ "core/core/controller.php", null, null ],
      [ "core/core/exception.php", null, null ],
      [ "core/core/form.php", null, null ],
      [ "core/core/model.php", null, null ],
      [ "core/core/validator.php", null, null ],
      [ "core/core/view.php", null, null ],
      [ "core/db/exception.php", null, null ],
      [ "core/db/generic.php", null, null ],
      [ "core/db/mysql.php", null, null ],
      [ "core/db/mysql_query_builder.php", null, null ],
      [ "core/db/pdo.php", null, null ],
      [ "core/db/pdo_mysql.php", null, null ],
      [ "core/lib/authentication.php", null, null ],
      [ "core/lib/debug.php", null, null ],
      [ "core/lib/files.php", null, null ],
      [ "core/lib/form.php", null, null ],
      [ "core/lib/url.php", null, null ],
      [ "templates/plainclean/index.php", null, null ],
      [ "templates/plainclean/template.php", null, null ],
      [ "templates/WhiteLove/index.php", null, null ],
      [ "templates/WhiteLove/template.php", null, null ]
    ] ],
    [ "Directories", "dirs.html", [
      [ "core", "dir_d8eabd6007182fedd088f3a02f6c7d2d.html", [
        [ "core", "dir_cdb29b942cadc7cbaf822e6ff7710af4.html", null ],
        [ "db", "dir_e1324a211d0a23155611aff2275ebb59.html", null ],
        [ "lib", "dir_589737913c4564314d04d7b8f2054bf2.html", null ]
      ] ],
      [ "templates", "dir_ab3859225b91877e6c63016efdf3d123.html", [
        [ "plainclean", "dir_7c252eae3d27367230bac50e6dc06bb3.html", null ],
        [ "WhiteLove", "dir_5941754e9c617a152ec16d0a40197b63.html", null ]
      ] ]
    ] ]
  ] ]
];

function createIndent(o,domNode,node,level)
{
  if (node.parentNode && node.parentNode.parentNode)
  {
    createIndent(o,domNode,node.parentNode,level+1);
  }
  var imgNode = document.createElement("img");
  if (level==0 && node.childrenData)
  {
    node.plus_img = imgNode;
    node.expandToggle = document.createElement("a");
    node.expandToggle.href = "javascript:void(0)";
    node.expandToggle.onclick = function() 
    {
      if (node.expanded) 
      {
        $(node.getChildrenUL()).slideUp("fast");
        if (node.isLast)
        {
          node.plus_img.src = node.relpath+"ftv2plastnode.png";
        }
        else
        {
          node.plus_img.src = node.relpath+"ftv2pnode.png";
        }
        node.expanded = false;
      } 
      else 
      {
        expandNode(o, node, false);
      }
    }
    node.expandToggle.appendChild(imgNode);
    domNode.appendChild(node.expandToggle);
  }
  else
  {
    domNode.appendChild(imgNode);
  }
  if (level==0)
  {
    if (node.isLast)
    {
      if (node.childrenData)
      {
        imgNode.src = node.relpath+"ftv2plastnode.png";
      }
      else
      {
        imgNode.src = node.relpath+"ftv2lastnode.png";
        domNode.appendChild(imgNode);
      }
    }
    else
    {
      if (node.childrenData)
      {
        imgNode.src = node.relpath+"ftv2pnode.png";
      }
      else
      {
        imgNode.src = node.relpath+"ftv2node.png";
        domNode.appendChild(imgNode);
      }
    }
  }
  else
  {
    if (node.isLast)
    {
      imgNode.src = node.relpath+"ftv2blank.png";
    }
    else
    {
      imgNode.src = node.relpath+"ftv2vertline.png";
    }
  }
  imgNode.border = "0";
}

function newNode(o, po, text, link, childrenData, lastNode)
{
  var node = new Object();
  node.children = Array();
  node.childrenData = childrenData;
  node.depth = po.depth + 1;
  node.relpath = po.relpath;
  node.isLast = lastNode;

  node.li = document.createElement("li");
  po.getChildrenUL().appendChild(node.li);
  node.parentNode = po;

  node.itemDiv = document.createElement("div");
  node.itemDiv.className = "item";

  node.labelSpan = document.createElement("span");
  node.labelSpan.className = "label";

  createIndent(o,node.itemDiv,node,0);
  node.itemDiv.appendChild(node.labelSpan);
  node.li.appendChild(node.itemDiv);

  var a = document.createElement("a");
  node.labelSpan.appendChild(a);
  node.label = document.createTextNode(text);
  a.appendChild(node.label);
  if (link) 
  {
    a.href = node.relpath+link;
  } 
  else 
  {
    if (childrenData != null) 
    {
      a.className = "nolink";
      a.href = "javascript:void(0)";
      a.onclick = node.expandToggle.onclick;
      node.expanded = false;
    }
  }

  node.childrenUL = null;
  node.getChildrenUL = function() 
  {
    if (!node.childrenUL) 
    {
      node.childrenUL = document.createElement("ul");
      node.childrenUL.className = "children_ul";
      node.childrenUL.style.display = "none";
      node.li.appendChild(node.childrenUL);
    }
    return node.childrenUL;
  };

  return node;
}

function showRoot()
{
  var headerHeight = $("#top").height();
  var footerHeight = $("#nav-path").height();
  var windowHeight = $(window).height() - headerHeight - footerHeight;
  navtree.scrollTo('#selected',0,{offset:-windowHeight/2});
}

function expandNode(o, node, imm)
{
  if (node.childrenData && !node.expanded) 
  {
    if (!node.childrenVisited) 
    {
      getNode(o, node);
    }
    if (imm)
    {
      $(node.getChildrenUL()).show();
    } 
    else 
    {
      $(node.getChildrenUL()).slideDown("fast",showRoot);
    }
    if (node.isLast)
    {
      node.plus_img.src = node.relpath+"ftv2mlastnode.png";
    }
    else
    {
      node.plus_img.src = node.relpath+"ftv2mnode.png";
    }
    node.expanded = true;
  }
}

function getNode(o, po)
{
  po.childrenVisited = true;
  var l = po.childrenData.length-1;
  for (var i in po.childrenData) 
  {
    var nodeData = po.childrenData[i];
    po.children[i] = newNode(o, po, nodeData[0], nodeData[1], nodeData[2],
        i==l);
  }
}

function findNavTreePage(url, data)
{
  var nodes = data;
  var result = null;
  for (var i in nodes) 
  {
    var d = nodes[i];
    if (d[1] == url) 
    {
      return new Array(i);
    }
    else if (d[2] != null) // array of children
    {
      result = findNavTreePage(url, d[2]);
      if (result != null) 
      {
        return (new Array(i).concat(result));
      }
    }
  }
  return null;
}

function initNavTree(toroot,relpath)
{
  var o = new Object();
  o.toroot = toroot;
  o.node = new Object();
  o.node.li = document.getElementById("nav-tree-contents");
  o.node.childrenData = NAVTREE;
  o.node.children = new Array();
  o.node.childrenUL = document.createElement("ul");
  o.node.getChildrenUL = function() { return o.node.childrenUL; };
  o.node.li.appendChild(o.node.childrenUL);
  o.node.depth = 0;
  o.node.relpath = relpath;

  getNode(o, o.node);

  o.breadcrumbs = findNavTreePage(toroot, NAVTREE);
  if (o.breadcrumbs == null)
  {
    o.breadcrumbs = findNavTreePage("index.html",NAVTREE);
  }
  if (o.breadcrumbs != null && o.breadcrumbs.length>0)
  {
    var p = o.node;
    for (var i in o.breadcrumbs) 
    {
      var j = o.breadcrumbs[i];
      p = p.children[j];
      expandNode(o,p,true);
    }
    p.itemDiv.className = p.itemDiv.className + " selected";
    p.itemDiv.id = "selected";
    $(window).load(showRoot);
  }
}

