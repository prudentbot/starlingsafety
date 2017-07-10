
// Reformat data from WP custom fields
var data = {};
var dataType = jQuery("#starling-map-data-type").val();

for (var i = 0; i < starling_map_script_vars.data.length; ++i){
  data[starling_map_script_vars.data[i].country_code] = starling_map_script_vars.data[i];
  if(!dataType){
    data[starling_map_script_vars.data[i].country_code].fillKey = data[starling_map_script_vars.data[i].country_code]["safety_score"];
  } else if(data[starling_map_script_vars.data[i].country_code][dataType]){
    data[starling_map_script_vars.data[i].country_code].fillKey = data[starling_map_script_vars.data[i].country_code][dataType];
  } else {
    data[starling_map_script_vars.data[i].country_code].fillKey = "defaultFill";
  }
}
function Zoom(args) {
  jQuery.extend(this, {
    $buttons:   jQuery(".zoom-button"),
    $info:      jQuery("#zoom-info"),
    scale:      { max: 50, currentShift: 0 },
    $container: args.$container,
    datamap:    args.datamap
  });

  this.init();
}

Zoom.prototype.init = function() {
  var paths = this.datamap.svg.selectAll("path"),
      subunits = this.datamap.svg.selectAll(".datamaps-subunit");

  // preserve stroke thickness
  paths.style("vector-effect", "non-scaling-stroke");

  // disable click on drag end
  subunits.call(
    d3.behavior.drag().on("dragend", function() {
      d3.event.sourceEvent.stopPropagation();
    })
  );

  this.scale.set = this._getScalesArray();
  this.d3Zoom = d3.behavior.zoom().scaleExtent([ 1, this.scale.max ]);
  this.d3Drag = d3.behavior.drag();

  this._displayPercentage(1);
  this.listen();
};

Zoom.prototype.listen = function() {
  this.$buttons.off("click").on("click", this._handleClick.bind(this));

  this.datamap.svg
    .call(this.d3Zoom.on("zoom", this._handleScroll.bind(this)))
    .on("wheel.zoom", null)
    .on("dblclick.zoom", null); // disable zoom on double-click
};

Zoom.prototype.reset = function() {
  this._shift("reset");
};

Zoom.prototype._handleScroll = function() {
  var translate = d3.event.translate,
      scale = d3.event.scale,
      limited = this._bound(translate, scale);

  this.scrolled = true;

  this._update(limited.translate, limited.scale);
};

Zoom.prototype._handleDrag = function() {
  var translate = d3.event.translate,
      scale = d3.event.scale,
      limited = this._bound(translate, 0);

  this.scrolled = true;

  this._update(limited.translate, 0);
};


Zoom.prototype._handleClick = function(event) {
  var direction = jQuery(event.target).data("zoom");
  this._shift(direction);
};

Zoom.prototype._shift = function(direction) {
  var center = [ this.$container.width() / 2, this.$container.height() / 2 ],
      translate = this.d3Zoom.translate(), translate0 = [], l = [],
      view = {
        x: translate[0],
        y: translate[1],
        k: this.d3Zoom.scale()
      }, bounded;

  translate0 = [
    (center[0] - view.x) / view.k,
    (center[1] - view.y) / view.k
  ];

	if (direction == "reset") {
  	view.k = 1;
    this.scrolled = true;
  } else {
  	view.k = this._getNextScale(direction);
  }

l = [ translate0[0] * view.k + view.x, translate0[1] * view.k + view.y ];

  view.x += center[0] - l[0];
  view.y += center[1] - l[1];

  bounded = this._bound([ view.x, view.y ], view.k);

  this._animate(bounded.translate, bounded.scale);
};

Zoom.prototype._bound = function(translate, scale) {
  var width = this.$container.width(),
      height = document.getElementById('basic_choropleth').clientHeight;
  //WHHHYYYYYYYYY?????

  translate[0] = Math.min(
    (width / height)  * (scale - 1),
    Math.max( width * (1 - scale), translate[0] )
  );

  translate[1] = Math.min(0, Math.max(height * (1 - scale), translate[1]));

  return { translate: translate, scale: scale };
};

Zoom.prototype._update = function(translate, scale) {
  this.d3Zoom
    .translate(translate)
    .scale(scale);

  this.datamap.svg.selectAll("g")
    .attr("transform", "translate(" + translate + ")scale(" + scale + ")");

  this._displayPercentage(scale);
};

Zoom.prototype._animate = function(translate, scale) {
  var _this = this,
      d3Zoom = this.d3Zoom;

  d3.transition().duration(350).tween("zoom", function() {
    var iTranslate = d3.interpolate(d3Zoom.translate(), translate),
        iScale = d3.interpolate(d3Zoom.scale(), scale);

		return function(t) {
      _this._update(iTranslate(t), iScale(t));
    };
  });
};

Zoom.prototype._displayPercentage = function(scale) {
  var value;

  value = Math.round(Math.log(scale) / Math.log(this.scale.max) * 100);
  this.$info.text(value + "%");
};

Zoom.prototype._getScalesArray = function() {
  var array = [],
      scaleMaxLog = Math.log(this.scale.max);

  for (var i = 0; i <= 10; i++) {
    array.push(Math.pow(Math.E, 0.1 * i * scaleMaxLog));
  }

  return array;
};

Zoom.prototype._getNextScale = function(direction) {
  var scaleSet = this.scale.set,
      currentScale = this.d3Zoom.scale(),
      lastShift = scaleSet.length - 1,
      shift, temp = [];

  if (this.scrolled) {

    for (shift = 0; shift <= lastShift; shift++) {
      temp.push(Math.abs(scaleSet[shift] - currentScale));
    }

    shift = temp.indexOf(Math.min.apply(null, temp));

    if (currentScale >= scaleSet[shift] && shift < lastShift) {
      shift++;
    }

    if (direction == "out" && shift > 0) {
      shift--;
    }

    this.scrolled = false;

  } else {

    shift = this.scale.currentShift;

    if (direction == "out") {
      shift > 0 && shift--;
    } else {
      shift < lastShift && shift++;
    }
  }

  this.scale.currentShift = shift;

  return scaleSet[shift];
};


function Datamap() {
	this.$container = jQuery("#basic_choropleth");
	this.instance = new Datamaps({
    scope: 'world',
    responsive:true,
    element: this.$container.get(0),
    done: this._handleMapReady.bind(this),
    geographyConfig: {
      popupTemplate: function(geography, data) {
        var text = jQuery("#starling-map-data-type option:selected").text();
        if (!text){
          text = "Safety Score";
        }
        var value = "";
        if(data.fillKey === "defaultFill")
          value = "Unknown";
        else
          value = data.fillKey +'/10';
        return '<div class="hoverinfo">' + geography.properties.name + '<div>' + text + ': ' + value + '</div></div>';
      },
      highlightFillColor: "#3DAAF7",
      highlightBorderColor: "#53b5fc"
    },
    fills: {
      defaultFill: "#C0C0C0",
      "1": "#e83a29",
      "2": "#e85431",
      "3": "#ff743b",
      "4": "#ff883d",
      "5": "#ff9e4b",
      "6": "#ffd851",
      "7": "#e8d54a",
      "8": "#e8e64a",
      "9": "#b7e84e",
      "10": "#83e856"
    },
    data:data
	});

}

Datamap.prototype._handleMapReady = function(datamap) {
	this.zoom = new Zoom({
  	$container: this.$container,
  	datamap: datamap
  });

  datamap.svg.selectAll('.datamaps-subunit').on('click', function(geography) {
    if (d3.event.defaultPrevented) return; // click suppressed
    var localData = basic_choropleth.instance.options.data[geography.id];
    if(localData && localData.permalink)
      window.location.href = localData.permalink;
  });

}

var basic_choropleth = new Datamap();


var updateMap = function () {
  var dataType = jQuery("#starling-map-data-type").val();

  for (var key in data) {
    // skip loop if the property is from prototype
    if (!data.hasOwnProperty(key)) continue;

    if(data[key][dataType])
      data[key].fillKey = data[key][dataType];
    else
      data[key].fillKey = "defaultFill";
  }
  basic_choropleth.instance.updateChoropleth(data);
}

jQuery("#starling-map-data-type").change(updateMap);
jQuery(".datamaps-subunit").css("cursor", "pointer");
