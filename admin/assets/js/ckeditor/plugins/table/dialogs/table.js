﻿(function(){function r(a){for(var f=0,l=0,k=0,m,e=a.$.rows.length;k<e;k++){m=a.$.rows[k];for(var d=f=0,c,b=m.cells.length;d<b;d++)c=m.cells[d],f+=c.colSpan;f>l&&(l=f)}return l}function o(a){return function(){var f=this.getValue(),f=!!(CKEDITOR.dialog.validate.integer()(f)&&0<f);f||(alert(a),this.select());return f}}function n(a,f){var l=function(e){return new CKEDITOR.dom.element(e,a.document)},n=a.editable(),m=a.plugins.dialogadvtab;return{title:a.lang.table.title,minWidth:310,minHeight:CKEDITOR.env.ie?
310:280,onLoad:function(){var e=this,a=e.getContentElement("advanced","advStyles");if(a)a.on("change",function(){var a=this.getStyle("width",""),b=e.getContentElement("info","txtWidth");b&&b.setValue(a,!0);a=this.getStyle("height","");(b=e.getContentElement("info","txtHeight"))&&b.setValue(a,!0)})},onShow:function(){var e=a.getSelection(),d=e.getRanges(),c,b=this.getContentElement("info","txtRows"),h=this.getContentElement("info","txtCols"),p=this.getContentElement("info","txtWidth"),g=this.getContentElement("info",
"txtHeight");"tableProperties"==f&&((e=e.getSelectedElement())&&e.is("table")?c=e:0<d.length&&(CKEDITOR.env.webkit&&d[0].shrink(CKEDITOR.NODE_ELEMENT),c=a.elementPath(d[0].getCommonAncestor(!0)).contains("table",1)),this._.selectedElement=c);c?(this.setupContent(c),b&&b.disable(),h&&h.disable()):(b&&b.enable(),h&&h.enable());p&&p.onChange();g&&g.onChange()},onOk:function(){var e=a.getSelection(),d=this._.selectedElement&&e.createBookmarks(),c=this._.selectedElement||l("table"),b={};this.commitContent(b,
c);if(b.info){b=b.info;if(!this._.selectedElement)for(var h=c.append(l("tbody")),f=parseInt(b.txtRows,10)||0,g=parseInt(b.txtCols,10)||0,i=0;i<f;i++)for(var j=h.append(l("tr")),k=0;k<g;k++){var m=j.append(l("td"));CKEDITOR.env.ie||m.append(l("br"))}f=b.selHeaders;if(!c.$.tHead&&("row"==f||"both"==f)){j=new CKEDITOR.dom.element(c.$.createTHead());h=c.getElementsByTag("tbody").getItem(0);h=h.getElementsByTag("tr").getItem(0);for(i=0;i<h.getChildCount();i++)g=h.getChild(i),g.type==CKEDITOR.NODE_ELEMENT&&
!g.data("cke-bookmark")&&(g.renameNode("th"),g.setAttribute("scope","col"));j.append(h.remove())}if(null!==c.$.tHead&&!("row"==f||"both"==f)){j=new CKEDITOR.dom.element(c.$.tHead);h=c.getElementsByTag("tbody").getItem(0);for(k=h.getFirst();0<j.getChildCount();){h=j.getFirst();for(i=0;i<h.getChildCount();i++)g=h.getChild(i),g.type==CKEDITOR.NODE_ELEMENT&&(g.renameNode("td"),g.removeAttribute("scope"));h.insertBefore(k)}j.remove()}if(!this.hasColumnHeaders&&("col"==f||"both"==f))for(j=0;j<c.$.rows.length;j++)g=
new CKEDITOR.dom.element(c.$.rows[j].cells[0]),g.renameNode("th"),g.setAttribute("scope","row");if(this.hasColumnHeaders&&!("col"==f||"both"==f))for(i=0;i<c.$.rows.length;i++)j=new CKEDITOR.dom.element(c.$.rows[i]),"tbody"==j.getParent().getName()&&(g=new CKEDITOR.dom.element(j.$.cells[0]),g.renameNode("td"),g.removeAttribute("scope"));b.txtHeight?c.setStyle("height",b.txtHeight):c.removeStyle("height");b.txtWidth?c.setStyle("width",b.txtWidth):c.removeStyle("width");c.getAttribute("style")||c.removeAttribute("style")}if(this._.selectedElement)try{e.selectBookmarks(d)}catch(n){}else a.insertElement(c),
setTimeout(function(){var e=new CKEDITOR.dom.element(c.$.rows[0].cells[0]),b=a.createRange();b.moveToPosition(e,CKEDITOR.POSITION_AFTER_START);b.select()},0)},contents:[{id:"info",label:a.lang.table.title,elements:[{type:"hbox",widths:[null,null],styles:["vertical-align:top"],children:[{type:"vbox",padding:0,children:[{type:"text",id:"txtRows","default":3,label:a.lang.table.rows,required:!0,controlStyle:"width:5em",validate:o(a.lang.table.invalidRows),setup:function(e){this.setValue(e.$.rows.length)},
commit:k},{type:"text",id:"txtCols","default":2,label:a.lang.table.columns,required:!0,controlStyle:"width:5em",validate:o(a.lang.table.invalidCols),setup:function(e){this.setValue(r(e))},commit:k},{type:"html",html:"&nbsp;"},{type:"select",id:"selHeaders","default":"",label:a.lang.table.headers,items:[[a.lang.table.headersNone,""],[a.lang.table.headersRow,"row"],[a.lang.table.headersColumn,"col"],[a.lang.table.headersBoth,"both"]],setup:function(e){var a=this.getDialog();a.hasColumnHeaders=!0;for(var c=
0;c<e.$.rows.length;c++){var b=e.$.rows[c].cells[0];if(b&&"th"!=b.nodeName.toLowerCase()){a.hasColumnHeaders=!1;break}}null!==e.$.tHead?this.setValue(a.hasColumnHeaders?"both":"row"):this.setValue(a.hasColumnHeaders?"col":"")},commit:k},{type:"text",id:"txtBorder","default":1,label:a.lang.table.border,controlStyle:"width:3em",validate:CKEDITOR.dialog.validate.number(a.lang.table.invalidBorder),setup:function(a){this.setValue(a.getAttribute("border")||"")},commit:function(a,d){this.getValue()?d.setAttribute("border",
this.getValue()):d.removeAttribute("border")}},{id:"cmbAlign",type:"select","default":"",label:a.lang.common.align,items:[[a.lang.common.notSet,""],[a.lang.common.alignLeft,"left"],[a.lang.common.alignCenter,"center"],[a.lang.common.alignRight,"right"]],setup:function(a){this.setValue(a.getAttribute("align")||"")},commit:function(a,d){this.getValue()?d.setAttribute("align",this.getValue()):d.removeAttribute("align")}}]},{type:"vbox",padding:0,children:[{type:"hbox",widths:["5em"],children:[{type:"text",
id:"txtWidth",controlStyle:"width:5em",label:a.lang.common.width,title:a.lang.common.cssLengthTooltip,"default":500>n.getSize("width")?"100%":500,getValue:q,validate:CKEDITOR.dialog.validate.cssLength(a.lang.common.invalidCssLength.replace("%1",a.lang.common.width)),onChange:function(){var a=this.getDialog().getContentElement("advanced","advStyles");a&&a.updateStyle("width",this.getValue())},setup:function(a){this.setValue(a.getStyle("width"))},commit:k}]},{type:"hbox",widths:["5em"],children:[{type:"text",
id:"txtHeight",controlStyle:"width:5em",label:a.lang.common.height,title:a.lang.common.cssLengthTooltip,"default":"",getValue:q,validate:CKEDITOR.dialog.validate.cssLength(a.lang.common.invalidCssLength.replace("%1",a.lang.common.height)),onChange:function(){var a=this.getDialog().getContentElement("advanced","advStyles");a&&a.updateStyle("height",this.getValue())},setup:function(a){(a=a.getStyle("height"))&&this.setValue(a)},commit:k}]},{type:"html",html:"&nbsp;"},{type:"text",id:"txtCellSpace",
controlStyle:"width:3em",label:a.lang.table.cellSpace,"default":1,validate:CKEDITOR.dialog.validate.number(a.lang.table.invalidCellSpacing),setup:function(a){this.setValue(a.getAttribute("cellSpacing")||"")},commit:function(a,d){this.getValue()?d.setAttribute("cellSpacing",this.getValue()):d.removeAttribute("cellSpacing")}},{type:"text",id:"txtCellPad",controlStyle:"width:3em",label:a.lang.table.cellPad,"default":1,validate:CKEDITOR.dialog.validate.number(a.lang.table.invalidCellPadding),setup:function(a){this.setValue(a.getAttribute("cellPadding")||
"")},commit:function(a,d){this.getValue()?d.setAttribute("cellPadding",this.getValue()):d.removeAttribute("cellPadding")}}]}]},{type:"html",align:"right",html:""},{type:"vbox",padding:0,children:[{type:"text",id:"txtCaption",label:a.lang.table.caption,setup:function(a){this.enable();a=a.getElementsByTag("caption");if(0<a.count()){var a=a.getItem(0),d=a.getFirst(CKEDITOR.dom.walker.nodeType(CKEDITOR.NODE_ELEMENT));d&&!d.equals(a.getBogus())?(this.disable(),this.setValue(a.getText())):(a=CKEDITOR.tools.trim(a.getText()),
this.setValue(a))}},commit:function(e,d){if(this.isEnabled()){var c=this.getValue(),b=d.getElementsByTag("caption");if(c)0<b.count()?(b=b.getItem(0),b.setHtml("")):(b=new CKEDITOR.dom.element("caption",a.document),d.getChildCount()?b.insertBefore(d.getFirst()):b.appendTo(d)),b.append(new CKEDITOR.dom.text(c,a.document));else if(0<b.count())for(c=b.count()-1;0<=c;c--)b.getItem(c).remove()}}},{type:"text",id:"txtSummary",label:a.lang.table.summary,setup:function(a){this.setValue(a.getAttribute("summary")||
"")},commit:function(a,d){this.getValue()?d.setAttribute("summary",this.getValue()):d.removeAttribute("summary")}}]}]},m&&m.createAdvancedTab(a)]}}var q=CKEDITOR.tools.cssLength,k=function(a){var f=this.id;a.info||(a.info={});a.info[f]=this.getValue()};CKEDITOR.dialog.add("table",function(a){return n(a,"table")});CKEDITOR.dialog.add("tableProperties",function(a){return n(a,"tableProperties")})})();