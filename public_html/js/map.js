$(document).ready(function (){
    let map = L.map('map').setView([36.31559, 59.56796],12);
    L.drawLocal.edit.toolbar.actions.clearAll.text = "پاک کردن";
    L.drawLocal.edit.toolbar.actions.cancel.text = "صرف نظر";
    L.drawLocal.edit.toolbar.actions.save.text = "ذخیره تغییرات";
    L.drawLocal.edit.handlers.edit.tooltip.subtext = '';
    L.drawLocal.edit.handlers.edit.tooltip.text    = '';
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Data © <a href="http://osm.org/copyright">OpenStreetMap</a>',
            maxZoom: 18
        }).addTo(map);
    let editableLayers = new L.FeatureGroup();
    map.addLayer(editableLayers);
    let options = {
        position: 'topleft',
        draw: {
            polygon: {
                allowIntersection: false,
                drawError: {
                    color: '#e1e100',
                    message: '<strong>خطا<strong>'
                },
                shapeOptions: {
                    color: '#97009c'
                }
            },
            polyline: false,
            circle: false,
            marker: false,
            circlemarker:false,
            rectangle: {
                allowIntersection: false,
                drawError: {
                    color: '#e1e100',
                    message: '<strong>خطا<strong>'
                },
                shapeOptions: {
                    color: '#97009c'
                }
            },
        },
        edit: {
            featureGroup: editableLayers,
            remove: true
        }
    };
    let drawControl = new L.Control.Draw(options);
    map.addControl(drawControl);
    map.addLayer(editableLayers);
    map.on('draw:created', function(e) {
        let layer = e.layer;
        let layer_count = editableLayers.getLayers();
        if(layer_count.length === 0) {
            editableLayers.addLayer(layer);
            $("#geoJson").val(JSON.stringify(layer.toGeoJSON())).trigger("change");
        }
    });
    map.on('draw:editstop', function(e) {
        let layer = e.layer;
        $("#geoJson").val(layer.toGeoJSON()).trigger("change");
    });
    map.on('draw:deleted', function(e) {
        $("#geoJson").val('').trigger("change");
    });
    $("#geoJson").on("change",function (){
        if ($(this).val() === "")
            $("#location_info").hide();
        else
            $("#location_info").show();
    });
    if (typeof state !== "undefined"){
        let polygon = L.geoJson(state);
        let newLayer = polygon.getLayers()[0];
        editableLayers.addLayer(newLayer);
        let coordination = state.geometry.coordinates[0][0];
        map.flyTo([coordination[1],coordination[0]], 16);
        $("#location_info").show();
    }
})
