(function($) {
    $.fn.orgChart = function(options) {
        var opts = $.extend({}, $.fn.orgChart.defaults, options);
        return new OrgChart($(this), opts);
    }

    $.fn.orgChart.defaults = {
        data: [{ id: 1, name: 'Root', type: 'auto', parent: 0, colapsed: false}],
        allowCollapse: true,
        types: ['auto'],
        typePrompt: '',
        typePrompValue: '',
        allowEdit: false,
        onExpandNode: null,
        onCollapseNode: null,
        onClickNode: null,
    };

    function OrgChart($container, opts) {
        var data = opts.data;
        var nodes = {};
        var rootNodes = [];
        this.opts = opts;
        this.$container = $container;
        var self = this;

        this.draw = function() {
            $container.empty().append(rootNodes[0].render(opts));
            $container.find('.node').click(function() {
                if (self.opts.onClickNode !== null) {
                    self.opts.onClickNode(nodes[$(this).attr('node-id')]);
                }
            });

            // add "add button" listener
            $container.find('.org-expand-button').click(function(e) {
                var thisId = $(this).parent().attr('node-id');

                if (self.opts.onExpandNode !== null) {
                    self.opts.onExpandNode(nodes[thisId]);
                } else {
                    self.expandNode(thisId);
                }
                e.stopPropagation();
            });

            $container.find('.org-collapse-button').click(function(e) {
                var thisId = $(this).parent().attr('node-id');

                if (self.opts.onCollapseNode !== null) {
                    self.opts.onCollapseNode(nodes[thisId]);
                } else {
                    self.collapseNode(thisId);
                }
                e.stopPropagation();
            });
        }
       
        this.getViewElement = function(id) {
            //console.log(id);
            if (typeof nodes[id] == "undefined") {
                return false;
            }
            data = nodes[id].data;
            console.log(data);
            var nameString = '',
                typeString = '',
                descString = '';
            if (typeof data.name !== 'undefined') {
                nameString = '<h2>' + data.name + '</h2>';
            }
            if (typeof data.type !== 'undefined') {
                $desc =  data.type
                typeString = '<p>' + $desc + '</p>';
            }
            if (typeof data.description !== 'undefined') {
                descString = '<p>' + data.description + '</p>';
            }

            $s1 = '<div class="nodeViewData">' + nameString + typeString + descString + '</div>';
            return $($s1);
        }
     
        this.expandNode = function(parentId) {
            nodes[parentId].data.colapsed=false;
            console.log(nodes[parentId].data);
            self.draw();
        }

        // this.expandNode = function(data) {
        //     nodes[id].colapsed=false;
        //     self.draw();
        // }

         this.collapseNode = function(id) {
            for (var i = 0; i < nodes[id].children.length; i++) {
                self.collapseNode(nodes[id].children[i].data.id);
            }
            //nodes[nodes[id].data.parent].collapseChild(id);
            nodes[id].data.colapsed=true;
            console.log(nodes[id].data);
            self.draw();
        }

        this.getData = function() {
            var outData = [];
            for (var i in nodes) {
                outData.push(nodes[i].data);
            }
            return outData;
        }

        // constructor
        for (var i in data) {
            var node = new Node(data[i]);
            nodes[data[i].id] = node;
        }

        // generate parent child tree
        for (var i in nodes) {
            if (typeof nodes[i].data.colapsed == 'undefined') {
                nodes[i].data.colapsed = false;
            }
            if (nodes[i].data.parent == 0) {
                rootNodes.push(nodes[i]);
            } else {
                nodes[nodes[i].data.parent].addChild(nodes[i]);
            }
        }

        // draw org chart
        $container.addClass('orgChart');
        self.draw();
    }

    function Node(data) {
        this.data = data;
        this.children = [];
        var self = this;

        this.addChild = function(childNode) {
            this.children.push(childNode);
        }

        this.collapseChild = function(id) {
            for (var i = 0; i < self.children.length; i++) {
                if (self.children[i].data.id == id) {
                    self.children.splice(i, 1);
                    return;
                }
            }
        }

        this.render = function(opts) {
            var childLength = self.children.length,
                mainTable;
            mainTable = "<table cellpadding='0' cellspacing='0' border='0'>";
            var nodeColspan = childLength > 0 ? 2 * childLength : 2;
            mainTable += "<tr><td colspan='" + nodeColspan + "'>" + self.formatNode(opts) + "</td></tr>";

            if (childLength > 0 && !self.data.colapsed) {
                var downLineTable = "<table cellpadding='0' cellspacing='0' border='0'><tr class='lines x'><td class='line left half'></td><td class='line right half'></td></table>";
                mainTable += "<tr class='lines'><td colspan='" + childLength * 2 + "'>" + downLineTable + '</td></tr>';

                var linesCols = '';
                for (var i = 0; i < childLength; i++) {
                    if (childLength == 1) {
                        linesCols += "<td class='line left half'></td>"; // keep vertical lines aligned if there's only 1 child
                    } else if (i == 0) {
                        linesCols += "<td class='line left'></td>"; // the first cell doesn't have a line in the top
                    } else {
                        linesCols += "<td class='line left top'></td>";
                    }

                    if (childLength == 1) {
                        linesCols += "<td class='line right half'></td>";
                    } else if (i == childLength - 1) {
                        linesCols += "<td class='line right'></td>";
                    } else {
                        linesCols += "<td class='line right top'></td>";
                    }
                }
                mainTable += "<tr class='lines v'>" + linesCols + "</tr>";

                mainTable += "<tr>";
                for (var i in self.children) {
                    mainTable += "<td colspan='2'>" + self.children[i].render(opts) + "</td>";
                }
                mainTable += "</tr>";
            }
            mainTable += '</table>';
            return mainTable;
        }

        this.leafNode = function() {
            return (self.children.length>0)?false:true;
        }
        this.parentNode = function() {
            isParent = false;
            return (self.children.length>0)?true:false;
        }
        this.getViewElement = function(opts) {
            var nameString = '',
                typeString = '',
                descString = '';
            if (typeof self.data.name !== 'undefined') {
                nameString = '<h2>' + self.data.name + '</h2>';
            }
            if (typeof self.data.type !== 'undefined') {
                typeString = '<p>' + self.data.type + '</p>';
            }
            if (typeof self.data.description !== 'undefined') {
                descString = '<p>' + self.data.description + '</p>';
            }

            $s1 = '<div class="nodeViewData">' + nameString + typeString + descString + '</div>';
            return $s1;
        }
        this.formatNode = function(opts) {
            viewElement = this.getViewElement(opts);
            isParent = this.parentNode();
            if (opts.allowCollapse && this.parentNode()) {
                if(self.data.colapsed){
                    var buttonsHtml = "<div class='org-expand-button'>&nbsp;</div>";
                }else{
                    var buttonsHtml = "<div class='org-collapse-button'>&nbsp;</div>";
                }
            } else {
                buttonsHtml = '';
            }
            return "<div class='node' node-id='" + this.data.id + "'>" + viewElement + buttonsHtml + "</div>";
        }
    }

})(jQuery);