define({ "api": [
  {
    "type": "post",
    "url": "admin/login/login",
    "title": "登录",
    "name": "login",
    "group": "login",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "username",
            "description": "<p>用户名.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>密码.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>token.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Login.php",
    "groupTitle": "login"
  },
  {
    "type": "post",
    "url": "admin/menu/access_menu",
    "title": "显示授权的菜单(权限设置)",
    "name": "access_menu",
    "group": "menu",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "role_id",
            "description": "<p>角色id,例如普通管理员id为2.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Menu.php",
    "groupTitle": "menu"
  },
  {
    "type": "post",
    "url": "admin/menu/add_submenu",
    "title": "添加子菜单(权限设置)",
    "name": "add_submenu",
    "group": "menu",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": false,
            "field": "data",
            "description": "<p>请求数据类型为数组，里面携带name,title等参数.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>菜单url.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "title",
            "description": "<p>菜单名称.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>菜单父id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>添加成功.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200成功.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Menu.php",
    "groupTitle": "menu"
  },
  {
    "type": "post",
    "url": "admin/menu/add_submenu",
    "title": "添加一级菜单(权限设置)",
    "name": "add_submenu",
    "group": "menu",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": false,
            "field": "data",
            "description": "<p>请求数据类型为数组，里面携带name,title等参数.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>菜单url.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "title",
            "description": "<p>菜单名称.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "icon",
            "description": "<p>菜单icon 例如el-icon-setting.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>添加成功.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200成功.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Menu.php",
    "groupTitle": "menu"
  },
  {
    "type": "get",
    "url": "admin/menu/admin_menu",
    "title": "获取后台显示菜单(左侧显示)",
    "name": "admin_menu",
    "group": "menu",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>菜单名称.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>菜单url.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "parent_id",
            "description": "<p>父id.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "children",
            "description": "<p>子分类.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "icon",
            "description": "<p>菜单图标.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回结果:",
          "content": "{\n \"id\": 1,\n \"parent_id\": 0,\n \"title\": \"系统设置\",\n \"name\": \"system_set\",\n \"icon\": \"el-icon-setting\",\n \"children\": [\n     {\n        \"id\": 3,\n        \"parent_id\": 1,\n        \"title\": \"系统设置\",\n        \"name\": \"网站信息\",\n        \"icon\": \"\",\n     }\n ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Menu.php",
    "groupTitle": "menu"
  },
  {
    "type": "post",
    "url": "admin/menu/del_menu",
    "title": "删除菜单(权限设置)",
    "name": "del_menu",
    "group": "menu",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": false,
            "field": "id",
            "description": "<p>某一菜单的id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>删除成功.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<ol start=\"200\"> <li></li> </ol>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>删除失败.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<ol start=\"101\"> <li></li> </ol>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Menu.php",
    "groupTitle": "menu"
  },
  {
    "type": "post",
    "url": "admin/menu/edit_menu",
    "title": "修改菜单(权限设置)",
    "name": "edit_menu",
    "group": "menu",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": false,
            "field": "data",
            "description": "<p>请求数据类型为数组，里面携带name,title等参数.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>菜单url.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "title",
            "description": "<p>菜单名称.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>菜单id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>修改成功.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200成功.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Menu.php",
    "groupTitle": "menu"
  },
  {
    "type": "get",
    "url": "admin/menu/get_all_menu",
    "title": "获取所有菜单(权限设置)",
    "name": "get_all_menu",
    "group": "menu",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>菜单名称.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>菜单url.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "parent_id",
            "description": "<p>父id.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "children",
            "description": "<p>子分类.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "icon",
            "description": "<p>菜单图标.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Menu.php",
    "groupTitle": "menu"
  },
  {
    "type": "get",
    "url": "admin/portal/get_category",
    "title": "分类获取",
    "name": "get_category",
    "group": "portal",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>分类名称.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "parent_id",
            "description": "<p>父id.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "children",
            "description": "<p>子分类.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态，1正常，0禁用.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "例子:",
          "content": "{\n \"id\": 1,\n \"parent_id\": 0,\n \"name\": \"服装类\",\n \"status\": 1,\n \"children\": [\n     {\n        \"id\": 3,\n        \"parent_id\": 1,\n        \"name\": \"男装\",\n        \"status\": 1,\n     }\n ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Portal.php",
    "groupTitle": "portal"
  },
  {
    "type": "post",
    "url": "admin/rabc/add_auth",
    "title": "角色授权(权限设置)",
    "name": "add_auth",
    "group": "rabc",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>角色id.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "menu_data",
            "description": "<p>菜单数据.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "rule_data",
            "description": "<p>权限规则数据.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>授权成功.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200成功.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Rabc.php",
    "groupTitle": "rabc"
  },
  {
    "type": "post",
    "url": "admin/rabc/add_role",
    "title": "添加角色(权限设置)",
    "name": "add_role",
    "group": "rabc",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>角色名称.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>角色描述.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态，0正常，-1禁用.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200成功.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Rabc.php",
    "groupTitle": "rabc"
  },
  {
    "type": "post",
    "url": "admin/rabc/del_role",
    "title": "删除角色(权限设置)",
    "name": "del_role",
    "group": "rabc",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>角色id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Rabc.php",
    "groupTitle": "rabc"
  },
  {
    "type": "post",
    "url": "admin/rabc/edit_rabc",
    "title": "修改角色(权限设置)",
    "name": "edit_rabc",
    "group": "rabc",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": false,
            "field": "data",
            "description": "<p>请求数据类型为数组，里面携带name,remark等参数.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>角色名称.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>角色描述.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>角色id.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态，0正常，-1禁用.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>修改成功.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200成功.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Rabc.php",
    "groupTitle": "rabc"
  },
  {
    "type": "post",
    "url": "admin/rabc/get_all_role",
    "title": "获取所有授权角色(权限设置)",
    "group": "rabc",
    "name": "get_all_role",
    "success": {
      "examples": [
        {
          "title": "返回结果:",
          "content": "[{\n\t\t\t\"id\": 1,\n\t\t\t\"create_time\": 1329633709,\n\t\t\t\"name\": \"超级管理员\",\n\t\t\t\"remark\": \"拥有网站最高管理员权限！\",\n\t\t\t\"status\": 0\n}, {\n\t\t\t\"id\": 2,\n\t\t\t\"create_time\": 1329633709,\n\t\t\t\"name\": \"普通管理员\",\n\t\t\t\"remark\": \"权限由最高管理员分配！\",\n\t\t\t\"status\": -1\n}]",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Rabc.php",
    "groupTitle": "rabc"
  },
  {
    "type": "post",
    "url": "admin/rabc/get_auth_role",
    "title": "获取授权角色",
    "name": "get_auth_role",
    "group": "rabc",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>数据集.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Rabc.php",
    "groupTitle": "rabc"
  },
  {
    "type": "post",
    "url": "admin/rules/del_rules",
    "title": "删除权限规则(权限设置)",
    "name": "del_rules",
    "group": "rules",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>规则id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>修改成功.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200成功.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Rules.php",
    "groupTitle": "rules"
  },
  {
    "type": "post",
    "url": "admin/rules/edit_rules",
    "title": "修改权限规则(权限设置)",
    "name": "edit_rules",
    "group": "rules",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>规则id.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>规则名称.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "app",
            "description": "<p>app.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "controller",
            "description": "<p>controller.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "action",
            "description": "<p>action.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "param",
            "description": "<p>param.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>数据集.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "msg",
            "description": "<p>信息提示.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Rules.php",
    "groupTitle": "rules"
  },
  {
    "type": "post",
    "url": "admin/rules/get_all_rules",
    "title": "获取所有授权规则(权限设置)",
    "name": "get_all_rules",
    "group": "rules",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>权限名称.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>权限url.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "parent_id",
            "description": "<p>父id.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "children",
            "description": "<p>子分类.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "结果:",
          "content": "[{\n\t\t\t\"id\": 163,\n\t\t\t\"parent_id\": 0,\n\t\t\t\"name\": \"admin\\/menu\\/default\",\n\t\t\t\"title\": \"菜单管理\",\n\t\t\t\"children\": [{\n\t\t\t\t\"id\": 166,\n\t\t\t\t\"parent_id\": 163,\n\t\t\t\t\"name\": \"admin\\/menu\\/get_all_menu\",\n\t\t\t\t\"title\": \"获取所有菜单\",\n\t\t\t}]\n\t}]",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Rules.php",
    "groupTitle": "rules"
  },
  {
    "type": "",
    "url": "{}",
    "title": "状态码说明",
    "name": "status_code",
    "group": "status_code",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "401",
            "description": "<p>未授权.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "403",
            "description": "<p>没有权限.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "405",
            "description": "<p>请求方法错误，一般为get,post.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "200",
            "description": "<p>请求成功.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "101",
            "description": "<p>请求失败.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/Test.php",
    "groupTitle": "status_code"
  },
  {
    "type": "post",
    "url": "admin/user/add_man_user",
    "title": "管理员添加",
    "group": "user",
    "name": "add_man_user",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>邮箱.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>用户名.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>密码.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "role_id",
            "description": "<p>角色id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>信息提示.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/User.php",
    "groupTitle": "user"
  },
  {
    "type": "post",
    "url": "admin/user/del_man_user",
    "title": "删除管理员",
    "group": "user",
    "name": "del_man_user",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>管理员id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>信息提示.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/User.php",
    "groupTitle": "user"
  },
  {
    "type": "post",
    "url": "admin/user/edit_man_user",
    "title": "编辑管理员",
    "group": "user",
    "name": "edit_man_user",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>邮箱.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>用户名.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>密码.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "role_id",
            "description": "<p>角色id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>信息提示.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/User.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "admin/user/get_all_man_user",
    "title": "获取全部管理员",
    "group": "user",
    "name": "get_all_man_user",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>规则id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>数据集.</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "../app/admin/controller/User.php",
    "groupTitle": "user"
  }
] });
