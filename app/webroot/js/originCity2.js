$(function()
{   
    //(2) 由来(1-7 = 1,12,2,13,123,23,3)    
    var t = { 1:1, 12:2, 2:3, 13:4, 123:5, 23:6, 3:7 };

    function calcBuildingPos(data)
    {
        var numOfBuilding = Object.keys(data).length;//7
        var areas = new Array();
        for(var i=1;i<=numOfBuilding;++i)
        {
            areas[i] = new building(0,0,0,0,0,0);
        }
        areas[t[1]].w = Math.sqrt(data[t[1]].numOfFiles);
	    areas[t[1]].h = areas[t[1]].w;
	    areas[t[2]].w = Math.sqrt(data[t[2]].numOfFiles);
	    areas[t[2]].h = areas[t[2]].w;
	    areas[t[3]].w = Math.sqrt(data[t[3]].numOfFiles);
	    areas[t[3]].h = areas[t[3]].w;
        
        var o123wh = Math.sqrt(data[t[123]].numOfFiles);
  
        areas[t[123]].w = o123wh;
	    areas[t[123]].h = o123wh; 
        
        areas[t[12]].h = o123wh;
	    areas[t[23]].w = o123wh;
	    areas[t[13]].w = o123wh;
        for(var i=1;i<=numOfBuilding;++i)
	    {
		    if (areas[i].h < 1)
			    areas[i].h = 1;
		    if (areas[i].w < 1)
			    areas[i].w = 1;
	    } 

    	areas[t[12]].w = data[t[12]].numOfFiles / areas[t[12]].h;
	    areas[t[23]].h = data[t[23]].numOfFiles / areas[t[23]].w;
	    areas[t[13]].h = data[t[13]].numOfFiles / areas[t[13]].w;
        for(var i=1;i<=numOfBuilding;++i)
	    {
		    if (areas[i].h < 1)
			    areas[i].h = 1;
		    if (areas[i].w < 1)
			    areas[i].w = 1;
	    }
        var offset = 20;
        

	    areas[t[12]].x = -(areas[t[123]].w + areas[t[12]].w)/2 - offset;
        areas[t[12]].y = areas[t[123]].y;

	    areas[t[13]].x = areas[t[123]].x;
        areas[t[13]].y = -(areas[t[123]].h + o123wh)/2 - offset;

	    areas[t[23]].x = areas[t[123]].x;
        areas[t[23]].y = (areas[t[123]].h + areas[t[23]].h)/2 + offset;

	    areas[t[1]].x = -(areas[t[123]].w + areas[t[1]].w)/2 - offset;
        areas[t[1]].y = (areas[t[123]].h + areas[t[1]].h)/2 + offset;

	    areas[t[2]].x = -(areas[t[123]].w + areas[t[2]].w)/2 - offset;
        areas[t[2]].y = -(areas[t[123]].h + areas[t[2]].h)/2 - offset;

	    areas[t[3]].x = (areas[t[123]].w + areas[t[3]].w)/2 + offset;
        areas[t[3]].y = areas[t[123]].y;    
	    
        return areas;
    }
    
    function calcBuildingHeight(areas,data)
    {
        var layerMap = [5,4,3,2,1,0,6];
        var boxes = new Array();
        var numOfBuilding = Object.keys(data).length;
        var numOfLayer = Object.keys(data[t[1]].layerHeight).length;
        for(var i=1;i<=numOfBuilding;++i)
        {
            var layers = new Array();
            for(var j=0;j<numOfLayer;++j)
            {
                var x = areas[i].x;
                var y = areas[i].y;
                var w = areas[i].w;
                var h = areas[i].h;
                var d = data[i].layerHeight[layerMap[j]];
                var z = d/2;
                if(0<j)
                {
                    z += layers[j-1].z + layers[j-1].d/2;
                }
                layers[j] = new building(x,y,z,w,h,d);
            }
            boxes[i]=layers;
        }        
        return boxes
    }
    function addPlane(renderer,scene)
    {
        //平面追加
        var plane =  new THREE.Mesh(                                      
             new THREE.PlaneGeometry(10000, 10000, 1, 1),
              new THREE.MeshLambertMaterial({ 
                color: 'white'             
                }));                      
        scene.add(plane);
    }
    function addAxisLine(scene)
    {
        var  axis = new THREE.AxisHelper(1000);          
        axis.position.set(0,0,1);        
        scene.add(axis);      
    }
    
    function addLight(scene)
    {
        var lightPos = [
            [-10000, -10000, 10000],
        ];
        
        for (var i = 0; i < lightPos.length; i++) {
            var directionalLight = new THREE.DirectionalLight('#FFFFFF', 1);
            directionalLight.position.set(lightPos[i][0],  lightPos[i][1], lightPos[i][2]);
            scene.add(directionalLight);
        }
        
        scene.add(new THREE.AmbientLight(0x333333));  
    }
    
    function addRenderControl(scene,camera,renderer)
    {
        var controls = new THREE.OrbitControls(camera, renderer.domElement);
    
        function renderLoop() {
            //OrbitControlsを使う場合必須
            window.requestAnimationFrame(renderLoop);
            controls.update();
            
            //実際に描画
            renderer.render(scene, camera);
        }
        renderLoop();
        
        window.addEventListener( 'resize', onWindowResize, false );

        function onWindowResize(){
            var canvas_wrapper = document.getElementById('canvas-wrapper');
            var width = canvas_wrapper.clientWidth;
            var height =  width*9/16;
            renderer.setSize(width, height);
            camera.aspect = width / height;
            camera.updateProjectionMatrix();
        }
    }
    function drawBuilding(boxes)
    {
        var scene = new THREE.Scene();
        var camera = new THREE.PerspectiveCamera(75, 600 / 400, 1, 1000);
    
        var maxZ = 0;
        for (var i = 1; i < boxes.length; i++)
        {
            var j = boxes[i].length-1;
            var p = boxes[i][j].z;
            if(maxZ < p)
                maxZ = p;
        }
        //var scale = 0.0000001;
        var scale = 300.0 / maxZ;
        camera.position.set(-boxes[t[2]][2].w*2, -boxes[t[2]][2].w*2, maxZ*scale*2);
        camera.up.set(0,0,1);   
        
        var renderer = new THREE.WebGLRenderer();
        var canvas_wrapper = document.getElementById('canvas-wrapper');
        canvas_wrapper.appendChild(renderer.domElement);
        
        var width = canvas_wrapper.clientWidth;
        var height =  width*9/16;
        renderer.setSize(width, height);

        //平面追加
        addPlane(renderer,scene);

        //x,y,z軸表示
        addAxisLine(scene);                          
        
        //環境光、平行光追加
        addLight(scene);
    
        var colors = [
            '#111111',//黒
            '#FA6565',//赤
            '#FECA61',//黄色
            '#71FD5E',//緑
            '#6BCDFF',//水色
            '#C869FF',//紫
            '#DDDDDD',//灰色
        ];
        
        //画面上方向に+y
        //画面右方向に+x
        //手前方向に+z
    
        for (var i = 1; i < boxes.length; i++)
        {
            for(var j=0;j<boxes[i].length;++j)
            {
                if(0<boxes[i][j].d)
                {
                    var material = new THREE.MeshPhongMaterial({color: colors[j]});
                    var geometry = new THREE.BoxGeometry(boxes[i][j].w,boxes[i][j].h,boxes[i][j].d*scale);
                    var cube = new THREE.Mesh(geometry, material);
                    cube.position.set(boxes[i][j].x,boxes[i][j].y,boxes[i][j].z*scale);
                    scene.add(cube);
                }
            }
        };
    
        addRenderControl(scene,camera,renderer);
    }
    
    //ページ切り替え直後、メトリクス非選択時は平面と軸のみ描画
    if(data!=null && data[0]!=0)
    {
        var areas = calcBuildingPos(data);
        var boxes = calcBuildingHeight(areas,data);
        drawBuilding(boxes);
    }
    else
    {
        var scene = new THREE.Scene();
        var camera = new THREE.PerspectiveCamera(75, 600 / 400, 1, 1000);
        camera.position.set(-100, -100, 100);
        camera.up.set(0,0,1);   
        
        var renderer = new THREE.WebGLRenderer();
        var canvas_wrapper = document.getElementById('canvas-wrapper');
        canvas_wrapper.appendChild(renderer.domElement);
        
        var width = canvas_wrapper.clientWidth;
        var height =  width*9/16;
        renderer.setSize(width, height);

        //平面追加
        addPlane(renderer,scene);

        //x,y,z軸表示
        addAxisLine(scene);                          
        
        //環境光、平行光追加
        addLight(scene);
    
        addRenderControl(scene,camera,renderer);    
    }
    function building(x,y,z,w,h,d)
    {
	    //コンストラクタとメンバ変数
	    this.x = x;
	    this.y = y;
        this.z = z;
        this.w = w;
        this.h = h;
        this.d = d;
    }
});