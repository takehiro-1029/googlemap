<template>
    <div class="app">
        <div id="map">
            <GmapMap :center="center" :zoom="zoom" ref="map" style="width: 100%; height: 100%;" @center_changed="onCenterChanged" @bounds_changed="onBoundsChanged">
                <GmapMarker v-for="(m,id) in marker_items"
                    :position="m.position"
                    :title="m.title"
                    :label="m.label"
                    :animation="m.animation"
                    :icon="m.icon"
                    :clickable="true" :draggable="false" :key="id" @click="marker_click(m.id)">
                </GmapMarker>
            </GmapMap>
        </div>
        <div>
            <p>【center】lat:{{info.center.lat}}　lng:{{info.center.lng}}</p>
            <p>【bounds_lat】lat:{{info.lat.min}}　lng:{{info.lat.max}}</p>
            <p>【bounds_lng】lat:{{info.lng.min}}　lng:{{info.lng.max}}</p>
            <div class="button" id="target" ref="scrollBox">
                <div　class="scroll_list scroll">
                    <button v-for="(m,id) in sortedItemsByAmount" v-on:mouseover="mouseover(m.id)" v-on:mouseleave="mouseleave(m.id)" v-bind:key="m.id" class="marker_button" ref="getParentWidth" :class="{ 'active' : m.isActive }">{{m.id}}</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                center: {lat: 35.75, lng: 139.73},
                zoom: 12,
                marker_items: [
                    {id:1,isActive: false,position: {lat: 35.71, lng: 139.73}, title: 'marker_1',animation:0,icon:{url: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=1|ff6633|000000',scaledSize: {width: 30, height: 45, f: 'px', b: 'px'} }},
                    {id:2,isActive: false,position: {lat: 35.72, lng: 139.71}, title: 'marker_2',animation:0,icon:{url: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=2|ff6633|000000',scaledSize: {width: 30, height: 45, f: 'px', b: 'px'} }},
                    {id:3,isActive: false,position: {lat: 35.73, lng: 139.74}, title: 'marker_3',animation:0,icon:{url: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=3|ff6633|000000',scaledSize: {width: 30, height: 45, f: 'px', b: 'px'} }},
                    {id:4,isActive: false,position: {lat: 35.70, lng: 139.69}, title: 'marker_4',animation:0,icon:{url: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=4|ff6633|000000',scaledSize: {width: 30, height: 45, f: 'px', b: 'px'} }},
                    {id:5,isActive: false,position: {lat: 35.74, lng: 139.70}, title: 'marker_5',animation:0,icon:{url: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=5|ff6633|000000',scaledSize: {width: 30, height: 45, f: 'px', b: 'px'} }},
                    {id:6,isActive: false,position: {lat: 35.75, lng: 139.68}, title: 'marker_6',animation:0,icon:{url: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=6|ff6633|000000',scaledSize: {width: 30, height: 45, f: 'px', b: 'px'} }},
                    {id:7,isActive: false,position: {lat: 35.76, lng: 139.70}, title: 'marker_7',animation:0,icon:{url: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=7|ff6633|000000',scaledSize: {width: 30, height: 45, f: 'px', b: 'px'} }}
                ],
                info: {
                    center:{lat:null, lng:null},
                    lat:{min: null, max: null},
                    lng:{min: null, max: null},
                },
                active_marker: -1,
            }
        },
        methods: {
            onCenterChanged (center) {
                this.info.center = {lat: center.lat(), lng: center.lng()}
            },
            onBoundsChanged (bounds) {
                let mapBounds = this.$refs.map.$mapObject.getBounds();
                this.info.lat = {min: mapBounds.Sa.i, max: mapBounds.Sa.j}
                this.info.lng = {min: mapBounds.Ya.i, max: mapBounds.Ya.j}
            },
            mouseover(value) {
              this.marker_items[value-1].animation = 1;
            },
            mouseleave(value) {
              this.marker_items[value-1].animation = 0;
            },
            marker_click(value) {
                if (this.active_marker === value-1){
                    return false;
                };
                return new Promise((resolve) => {
                        if(this.active_marker >= 0){
                            this.marker_items[this.active_marker].isActive = false; 
                        };      
                        resolve();   
                })
                .then(() => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            let target = '';
                            let length = this.marker_items.length;
                            if((value > 1) && (value !== length)){
                                target = this.$refs.getParentWidth[value-2];
                            }else if(value === 1){
                                target = this.$refs.getParentWidth[value-1];
                            }else if(value === length){
                                target = this.$refs.getParentWidth[value-3];
                            }
                            $('.scroll').css(`transform`, `translateX(${-(target.offsetLeft)}px)`);
                            resolve();
                        }, 250)
                    })
                })
                .then(() => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            this.marker_items[value-1].isActive = true;
                            this.active_marker = value-1;
                            resolve();
                        }, 500)
                    })
                })
            }
        },
        computed: {
        // 並べ替え
            sortedItemsByAmount(){
                return this.marker_items.sort((a, b) => {
                    return (a.id < b.id) ? -1 : (a.id > b.id) ? 1 : 0;
                });;
            }
        }
    }
</script>
<style  scoped>
    #map{
        height:500px;
    }
    .vue-map{
        width: 100%;
        height: 500px;
    }
    .button{
        white-space: nowrap;
        overflow-x: scroll;
        margin-bottom: 50px;
        width: 50%;
        overflow: hidden;
        margin: 0 auto;
    }
    .button::-webkit-scrollbar{
        height: 10px;
    }
    .button::-webkit-scrollbar-track{
        background: #fff;
        border-left: solid 1px #ececec;
    }
    .button::-webkit-scrollbar-thumb{
        background: #ccc;
        border-radius: 10px;
        box-shadow: inset 0 0 0 2px #fff;
    }
    .marker_button{
        width: 32%;
        box-sizing: border-box;
        transition: all 0.25s ease-out;
        background: #fff;
        border-color: #ececec69;
    }
    .marker_button:not(:first-of-type){
        margin-left: 2%;
    }
    .scroll_list{
        transform: translateX(0);
        transition: all 0.25s ease-out;
        line-height: 50px;
    }
    .active{
        transform: translateY(-10px);
        background: aqua;
    } 
</style>