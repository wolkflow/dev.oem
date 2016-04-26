(function (console, $hx_exports, $global) { "use strict";
$hx_exports.openfl = $hx_exports.openfl || {};
$hx_exports.lime = $hx_exports.lime || {};
$hx_exports.ru = $hx_exports.ru || {};
$hx_exports.ru.octasoft = $hx_exports.ru.octasoft || {};
$hx_exports.ru.octasoft.oem = $hx_exports.ru.octasoft.oem || {};
$hx_exports.ru.octasoft.oem.designer = $hx_exports.ru.octasoft.oem.designer || {};
var $hxClasses = {},$estr = function() { return js_Boot.__string_rec(this,''); };
function $extend(from, fields) {
	function Inherit() {} Inherit.prototype = from; var proto = new Inherit();
	for (var name in fields) proto[name] = fields[name];
	if( fields.toString !== Object.prototype.toString ) proto.toString = fields.toString;
	return proto;
}
var ApplicationMain = function() { };
$hxClasses["ApplicationMain"] = ApplicationMain;
ApplicationMain.__name__ = true;
ApplicationMain.config = null;
ApplicationMain.preloader = null;
ApplicationMain.create = function() {
	var app = new openfl_display_Application();
	app.create(ApplicationMain.config);
	var display = new NMEPreloader();
	ApplicationMain.preloader = new openfl_display_Preloader(display);
	app.setPreloader(ApplicationMain.preloader);
	ApplicationMain.preloader.onComplete.add(ApplicationMain.init);
	ApplicationMain.preloader.create(ApplicationMain.config);
	var urls = [];
	var types = [];
	urls.push("Gotham Pro Bold");
	types.push("FONT");
	urls.push("Gotham Pro Regular");
	types.push("FONT");
	urls.push("assets/images/border1.png");
	types.push("IMAGE");
	urls.push("assets/images/border2.png");
	types.push("IMAGE");
	urls.push("assets/images/decor.png");
	types.push("IMAGE");
	urls.push("assets/images/delete.png");
	types.push("IMAGE");
	urls.push("assets/images/popup_left.png");
	types.push("IMAGE");
	urls.push("assets/images/popup_right.png");
	types.push("IMAGE");
	urls.push("assets/images/popup_top.png");
	types.push("IMAGE");
	urls.push("assets/images/redo.png");
	types.push("IMAGE");
	urls.push("assets/images/rotate.png");
	types.push("IMAGE");
	urls.push("assets/images/shelf_bg1.png");
	types.push("IMAGE");
	urls.push("assets/images/undo.png");
	types.push("IMAGE");
	urls.push("assets/images/zoomIn.png");
	types.push("IMAGE");
	urls.push("assets/images/zoomOut.png");
	types.push("IMAGE");
	if(ApplicationMain.config.assetsPrefix != null) {
		var _g1 = 0;
		var _g = urls.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(types[i] != "FONT") urls[i] = ApplicationMain.config.assetsPrefix + urls[i];
		}
	}
	ApplicationMain.preloader.load(urls,types);
	var result = app.exec();
};
ApplicationMain.init = function() {
	var loaded = 0;
	var total = 0;
	var library_onLoad = function(__) {
		loaded++;
		if(loaded == total) ApplicationMain.start();
	};
	ApplicationMain.preloader = null;
	if(total == 0) ApplicationMain.start();
};
ApplicationMain.main = function() {
	ApplicationMain.config = { build : "1885", company : "Octasoft", file : "designer", fps : 60, name : "Editor", orientation : "", packageName : "ru.octasoft.oem.designer", version : "1.0.0", windows : [{ antialiasing : 0, background : 16777215, borderless : false, depthBuffer : false, display : 0, fullscreen : false, hardware : true, height : 0, parameters : "{}", resizable : true, stencilBuffer : true, title : "Editor", vsync : false, width : 0, x : null, y : null}]};
};
ApplicationMain.start = function() {
	var hasMain = false;
	var entryPoint = Type.resolveClass("ru.octasoft.oem.designer.Main");
	var _g = 0;
	var _g1 = Type.getClassFields(entryPoint);
	while(_g < _g1.length) {
		var methodName = _g1[_g];
		++_g;
		if(methodName == "main") {
			hasMain = true;
			break;
		}
	}
	lime_Assets.initialize();
	if(hasMain) Reflect.callMethod(entryPoint,Reflect.field(entryPoint,"main"),[]); else {
		var instance = Type.createInstance(DocumentClass,[]);
	}
	if(openfl_Lib.current.stage.window.__fullscreen) openfl_Lib.current.stage.dispatchEvent(new openfl_events_FullScreenEvent(openfl_events_FullScreenEvent.FULL_SCREEN,false,false,true,true));
	openfl_Lib.current.stage.dispatchEvent(new openfl_events_Event(openfl_events_Event.RESIZE,false,false));
};
var openfl_events_IEventDispatcher = function() { };
$hxClasses["openfl.events.IEventDispatcher"] = openfl_events_IEventDispatcher;
openfl_events_IEventDispatcher.__name__ = true;
var openfl_events_EventDispatcher = function(target) {
	if(target != null) this.__targetDispatcher = target;
};
$hxClasses["openfl.events.EventDispatcher"] = openfl_events_EventDispatcher;
openfl_events_EventDispatcher.__name__ = true;
openfl_events_EventDispatcher.__interfaces__ = [openfl_events_IEventDispatcher];
openfl_events_EventDispatcher.__sortByPriority = function(l1,l2) {
	if(l1.priority == l2.priority) return 0; else if(l1.priority > l2.priority) return -1; else return 1;
};
openfl_events_EventDispatcher.prototype = {
	addEventListener: function(type,listener,useCapture,priority,useWeakReference) {
		if(useWeakReference == null) useWeakReference = false;
		if(priority == null) priority = 0;
		if(useCapture == null) useCapture = false;
		if(this.__eventMap == null) {
			this.__dispatching = new haxe_ds_StringMap();
			this.__eventMap = new haxe_ds_StringMap();
			this.__newEventMap = new haxe_ds_StringMap();
		}
		if(!this.__eventMap.exists(type)) {
			var list = [];
			list.push(new openfl_events__$EventDispatcher_Listener(listener,useCapture,priority));
			this.__eventMap.set(type,list);
		} else {
			var list1;
			if(this.__dispatching.get(type) == true) {
				if(!this.__newEventMap.exists(type)) {
					var _this = this.__eventMap.get(type);
					list1 = _this.slice();
					this.__newEventMap.set(type,list1);
				} else list1 = this.__newEventMap.get(type);
			} else list1 = this.__eventMap.get(type);
			var _g1 = 0;
			var _g = list1.length;
			while(_g1 < _g) {
				var i = _g1++;
				if(Reflect.compareMethods(list1[i].callback,listener)) return;
			}
			list1.push(new openfl_events__$EventDispatcher_Listener(listener,useCapture,priority));
			list1.sort(openfl_events_EventDispatcher.__sortByPriority);
		}
	}
	,dispatchEvent: function(event) {
		if(this.__targetDispatcher != null) event.target = this.__targetDispatcher; else event.target = this;
		return this.__dispatchEvent(event);
	}
	,hasEventListener: function(type) {
		if(this.__eventMap == null) return false;
		if(this.__dispatching.get(type) == true && this.__newEventMap.exists(type)) return this.__newEventMap.get(type).length > 0; else return this.__eventMap.exists(type);
	}
	,removeEventListener: function(type,listener,capture) {
		if(capture == null) capture = false;
		if(this.__eventMap == null) return;
		var list = this.__eventMap.get(type);
		if(list == null) return;
		var dispatching = this.__dispatching.get(type) == true;
		if(dispatching) {
			if(!this.__newEventMap.exists(type)) {
				var _this = this.__eventMap.get(type);
				list = _this.slice();
				this.__newEventMap.set(type,list);
			} else list = this.__newEventMap.get(type);
		}
		var _g1 = 0;
		var _g = list.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(list[i].match(listener,capture)) {
				list.splice(i,1);
				break;
			}
		}
		if(!dispatching) {
			if(list.length == 0) this.__eventMap.remove(type);
			if(!this.__eventMap.iterator().hasNext()) {
				this.__eventMap = null;
				this.__newEventMap = null;
			}
		}
	}
	,__dispatchEvent: function(event) {
		if(this.__eventMap == null || event == null) return false;
		var type = event.type;
		var list;
		if(this.__dispatching.get(type) == true) {
			list = this.__newEventMap.get(type);
			if(list == null) return false;
			list = list.slice();
		} else {
			list = this.__eventMap.get(type);
			if(list == null) return false;
			this.__dispatching.set(type,true);
		}
		if(event.target == null) {
			if(this.__targetDispatcher != null) event.target = this.__targetDispatcher; else event.target = this;
		}
		event.currentTarget = this;
		var capture = event.eventPhase == openfl_events_EventPhase.CAPTURING_PHASE;
		var index = 0;
		var listener;
		while(index < list.length) {
			listener = list[index];
			if(listener.useCapture == capture) {
				listener.callback(event);
				if(event.__isCancelledNow) break;
			}
			if(listener == list[index]) index++;
		}
		if(this.__newEventMap != null && this.__newEventMap.exists(type)) {
			var list1 = this.__newEventMap.get(type);
			if(list1.length > 0) this.__eventMap.set(type,list1); else this.__eventMap.remove(type);
			if(!this.__eventMap.iterator().hasNext()) {
				this.__eventMap = null;
				this.__newEventMap = null;
			} else this.__newEventMap.remove(type);
		}
		this.__dispatching.set(event.type,false);
		return true;
	}
	,__class__: openfl_events_EventDispatcher
};
var openfl_display_IBitmapDrawable = function() { };
$hxClasses["openfl.display.IBitmapDrawable"] = openfl_display_IBitmapDrawable;
openfl_display_IBitmapDrawable.__name__ = true;
openfl_display_IBitmapDrawable.prototype = {
	__class__: openfl_display_IBitmapDrawable
};
var openfl_display_DisplayObject = function() {
	this.__cacheAsBitmapSmooth = true;
	this.__cacheAsBitmap = false;
	this.__maskCached = false;
	openfl_events_EventDispatcher.call(this);
	this.__alpha = 1;
	this.__transform = new openfl_geom_Matrix();
	this.__visible = true;
	this.__rotation = 0;
	this.__rotationSine = 0;
	this.__rotationCosine = 1;
	this.__renderTransform = new openfl_geom_Matrix();
	this.__offset = new openfl_geom_Point();
	this.__worldOffset = new openfl_geom_Point();
	this.__worldAlpha = 1;
	this.__worldTransform = new openfl_geom_Matrix();
	this.__worldColorTransform = new openfl_geom_ColorTransform();
	this.set_name("instance" + ++openfl_display_DisplayObject.__instanceCount);
};
$hxClasses["openfl.display.DisplayObject"] = openfl_display_DisplayObject;
openfl_display_DisplayObject.__name__ = true;
openfl_display_DisplayObject.__interfaces__ = [openfl_display_IBitmapDrawable];
openfl_display_DisplayObject.__super__ = openfl_events_EventDispatcher;
openfl_display_DisplayObject.prototype = $extend(openfl_events_EventDispatcher.prototype,{
	getBounds: function(targetCoordinateSpace) {
		var matrix;
		if(targetCoordinateSpace != null) {
			matrix = this.__getWorldTransform().clone();
			matrix.concat(targetCoordinateSpace.__getWorldTransform().clone().invert());
		} else {
			matrix = openfl_geom_Matrix.__temp;
			matrix.identity();
		}
		var bounds = new openfl_geom_Rectangle();
		this.__getBounds(bounds,matrix);
		return bounds;
	}
	,globalToLocal: function(pos) {
		pos = pos.clone();
		this.__getWorldTransform().__transformInversePoint(pos);
		return pos;
	}
	,localToGlobal: function(point) {
		return this.__getWorldTransform().transformPoint(point);
	}
	,__broadcast: function(event,notifyChilden) {
		if(this.__eventMap != null && this.hasEventListener(event.type)) {
			var result = openfl_events_EventDispatcher.prototype.__dispatchEvent.call(this,event);
			if(event.__isCancelled) return true;
			return result;
		}
		return false;
	}
	,__dispatchEvent: function(event) {
		var result = openfl_events_EventDispatcher.prototype.__dispatchEvent.call(this,event);
		if(event.__isCancelled) return true;
		if(event.bubbles && this.parent != null && this.parent != this) {
			event.eventPhase = openfl_events_EventPhase.BUBBLING_PHASE;
			if(event.target == null) event.target = this;
			this.parent.__dispatchEvent(event);
		}
		return result;
	}
	,__enterFrame: function(deltaTime) {
	}
	,__getBounds: function(rect,matrix) {
		if(this.__graphics != null) this.__graphics.__getBounds(rect,matrix);
	}
	,__getRenderBounds: function(rect,matrix) {
		if(this.__scrollRect == null) this.__getBounds(rect,matrix); else {
			var r = openfl_geom_Rectangle.__temp;
			r.copyFrom(this.__scrollRect);
			r.__transform(r,matrix);
			rect.__expand(matrix.tx,matrix.ty,r.width,r.height);
		}
	}
	,__getCursor: function() {
		return null;
	}
	,__getInteractive: function(stack) {
		return false;
	}
	,__getWorldTransform: function() {
		if(this.__transformDirty || openfl_display_DisplayObject.__worldTransformDirty > 0) {
			var list = [];
			var current = this;
			var transformDirty = this.__transformDirty;
			if(this.parent == null) {
				if(transformDirty) this.__update(true,false);
			} else while(current.parent != null) {
				list.push(current);
				current = current.parent;
				if(current.__transformDirty) transformDirty = true;
			}
			if(transformDirty) {
				var i = list.length;
				while(--i >= 0) list[i].__update(true,false);
			}
		}
		return this.__worldTransform;
	}
	,__hitTest: function(x,y,shapeFlag,stack,interactiveOnly) {
		if(this.__graphics != null) {
			if(!this.get_visible() || this.__isMask) return false;
			if(this.get_mask() != null && !this.get_mask().__hitTestMask(x,y)) return false;
			if(this.__graphics.__hitTest(x,y,shapeFlag,this.__getWorldTransform())) {
				if(stack != null && !interactiveOnly) stack.push(this);
				return true;
			}
		}
		return false;
	}
	,__hitTestMask: function(x,y) {
		if(this.__graphics != null) {
			if(this.__graphics.__hitTest(x,y,true,this.__getWorldTransform())) return true;
		}
		return false;
	}
	,__renderCairo: function(renderSession) {
		if(this.__graphics != null) openfl__$internal_renderer_cairo_CairoShape.render(this,renderSession);
	}
	,__renderCairoMask: function(renderSession) {
		if(this.__graphics != null) openfl__$internal_renderer_cairo_CairoGraphics.renderMask(this.__graphics,renderSession);
	}
	,__renderCanvas: function(renderSession) {
		if(this.__graphics != null) openfl__$internal_renderer_canvas_CanvasShape.render(this,renderSession);
	}
	,__renderCanvasMask: function(renderSession) {
		if(this.__graphics != null) openfl__$internal_renderer_canvas_CanvasGraphics.renderMask(this.__graphics,renderSession);
	}
	,__renderDOM: function(renderSession) {
		if(this.__graphics != null) openfl__$internal_renderer_dom_DOMShape.render(this,renderSession);
	}
	,__renderGL: function(renderSession) {
		if(!this.__renderable || this.__worldAlpha <= 0) return;
		if(this.__cacheAsBitmap) {
			this.__cacheGL(renderSession);
			return;
		}
		if(this.__scrollRect != null) renderSession.maskManager.pushRect(this.__scrollRect,this.__renderTransform);
		if(this.__mask != null && this.__maskGraphics != null && this.__maskGraphics.__commands.get_length() > 0) renderSession.maskManager.pushMask(this);
		if(this.__graphics != null) {
			if(this.__graphics.__hardware) openfl__$internal_renderer_opengl_utils_GraphicsRenderer.render(this,renderSession); else {
				openfl__$internal_renderer_canvas_CanvasGraphics.render(this.__graphics,renderSession);
				openfl__$internal_renderer_opengl_GLRenderer.renderBitmap(this,renderSession);
			}
		}
		if(this.__mask != null && this.__maskGraphics != null && this.__maskGraphics.__commands.get_length() > 0) renderSession.maskManager.popMask();
		if(this.__scrollRect != null) renderSession.maskManager.popRect();
	}
	,__cacheGL: function(renderSession) {
		var hasCacheMatrix = this.__cacheAsBitmapMatrix != null;
		var x = this.__cachedBitmapBounds.x;
		var y = this.__cachedBitmapBounds.y;
		var w = this.__cachedBitmapBounds.width;
		var h = this.__cachedBitmapBounds.height;
		if(this.__cacheGLMatrix == null) this.__cacheGLMatrix = new openfl_geom_Matrix();
		if(hasCacheMatrix) {
			var bmpBounds = openfl_geom_Rectangle.__temp;
			this.__cachedBitmapBounds.__transform(bmpBounds,this.__cacheAsBitmapMatrix);
			x = bmpBounds.x;
			y = bmpBounds.y;
			w = bmpBounds.width;
			h = bmpBounds.height;
			this.__cacheGLMatrix = this.__cacheAsBitmapMatrix.clone();
		} else this.__cacheGLMatrix.identity();
		if(w <= 0 && h <= 0) throw new js__$Boot_HaxeError("Error creating a cached bitmap. The texture size is " + w + "x" + h);
		if(this.__updateCachedBitmap || this.__updateFilters) {
			if(this.__cachedFilterBounds != null) {
				w += Math.abs(this.__cachedFilterBounds.x) + Math.abs(this.__cachedFilterBounds.width);
				h += Math.abs(this.__cachedFilterBounds.y) + Math.abs(this.__cachedFilterBounds.height);
			}
			if(this.__cachedBitmap == null) this.__cachedBitmap = openfl_display_BitmapData.__asRenderTexture();
			this.__cachedBitmap.__resize(Math.ceil(w),Math.ceil(h));
			var m = this.__cacheGLMatrix.clone();
			m.translate(-x,-y);
			var shader = this.__shader;
			this.__shader = null;
			this.__cachedBitmap.__drawGL(renderSession,this,m,null,null,null,true,false,true);
			this.__shader = shader;
			this.__updateCachedBitmap = false;
		}
		if(this.__updateFilters) {
			openfl_filters_BitmapFilter.__applyFilters(this.__filters,renderSession,this.__cachedBitmap,this.__cachedBitmap,null,null);
			this.__updateFilters = false;
		}
		this.__cacheGLMatrix.invert();
		this.__cacheGLMatrix.__translateTransformed(x,y);
		this.__cacheGLMatrix.concat(this.__renderTransform);
		this.__cacheGLMatrix.translate(this.__offset.x,this.__offset.y);
		renderSession.spriteBatch.renderBitmapData(this.__cachedBitmap,this.__cacheAsBitmapSmooth,this.__cacheGLMatrix,this.__worldColorTransform,this.__worldAlpha,this.blendMode,this.__shader,openfl_display_PixelSnapping.ALWAYS);
	}
	,__setStageReference: function(stage) {
		if(this.stage != stage) {
			if(this.stage != null) {
				if(this.stage.get_focus() == this) this.stage.set_focus(null);
				this.dispatchEvent(new openfl_events_Event(openfl_events_Event.REMOVED_FROM_STAGE,false,false));
			}
			this.stage = stage;
			if(stage != null) this.dispatchEvent(new openfl_events_Event(openfl_events_Event.ADDED_TO_STAGE,false,false));
		}
	}
	,__setRenderDirty: function() {
		if(!this.__renderDirty) {
			this.__updateCachedBitmap = true;
			this.__updateFilters = this.get_filters() != null && this.get_filters().length > 0;
			this.__renderDirty = true;
			openfl_display_DisplayObject.__worldRenderDirty++;
		}
	}
	,__updateTransforms: function(overrideTransfrom) {
		var overrided = overrideTransfrom != null;
		var local;
		if(overrided) local = new openfl_geom_Matrix(overrideTransfrom.a,overrideTransfrom.b,overrideTransfrom.c,overrideTransfrom.d,overrideTransfrom.tx,overrideTransfrom.ty); else local = this.__transform;
		if(!overrided && this.parent != null) {
			var parentTransform = this.parent.__worldTransform;
			this.__worldTransform.a = local.a * parentTransform.a + local.b * parentTransform.c;
			this.__worldTransform.b = local.a * parentTransform.b + local.b * parentTransform.d;
			this.__worldTransform.c = local.c * parentTransform.a + local.d * parentTransform.c;
			this.__worldTransform.d = local.c * parentTransform.b + local.d * parentTransform.d;
			this.__worldTransform.tx = local.tx * parentTransform.a + local.ty * parentTransform.c + parentTransform.tx;
			this.__worldTransform.ty = local.tx * parentTransform.b + local.ty * parentTransform.d + parentTransform.ty;
			this.__worldOffset.copyFrom(this.parent.__worldOffset);
		} else {
			this.__worldTransform.copyFrom(local);
			this.__worldOffset.setTo(0,0);
		}
		if(this.__scrollRect != null) {
			this.__offset = this.__worldTransform.deltaTransformPoint(this.__scrollRect.get_topLeft());
			this.__worldOffset.offset(this.__offset.x,this.__offset.y);
		} else this.__offset.setTo(0,0);
		this.__renderTransform.copyFrom(this.__worldTransform);
		this.__renderTransform.translate(-this.__worldOffset.x,-this.__worldOffset.y);
	}
	,__update: function(transformOnly,updateChildren,maskGraphics) {
		this.__renderable = this.get_visible() && this.get_scaleX() != 0 && this.get_scaleY() != 0 && !this.__isMask;
		this.__updateTransforms();
		if(this.parent != null && this.__isMask) this.__maskCached = false;
		if(updateChildren && this.__transformDirty) {
			this.__transformDirty = false;
			openfl_display_DisplayObject.__worldTransformDirty--;
		}
		if(!transformOnly && this.__mask != null && !this.__mask.__maskCached) {
			if(this.__maskGraphics == null) this.__maskGraphics = new openfl_display_Graphics();
			this.__maskGraphics.clear();
			this.__mask.__update(true,true,this.__maskGraphics);
			this.__mask.__maskCached = true;
		}
		if(maskGraphics != null) this.__updateMask(maskGraphics);
		if(!transformOnly && this.__cacheAsBitmap) {
			if(this.__updateCachedBitmap || this.__updateFilters) {
				if(this.__cachedBitmapBounds == null) this.__cachedBitmapBounds = new openfl_geom_Rectangle();
				if(this.cacheAsBitmapBounds != null) this.__cachedBitmapBounds.copyFrom(this.cacheAsBitmapBounds); else {
					this.__cachedBitmapBounds.setEmpty();
					this.__getRenderBounds(this.__cachedBitmapBounds,openfl_geom_Matrix.__identity);
				}
				if(this.__filters != null) {
					if(this.__cachedFilterBounds == null) this.__cachedFilterBounds = new openfl_geom_Rectangle();
					this.__cachedFilterBounds.setEmpty();
					openfl_filters_BitmapFilter.__expandBounds(this.__filters,this.__cachedFilterBounds,openfl_geom_Matrix.__identity);
					this.__cachedBitmapBounds.x += this.__cachedFilterBounds.x;
					this.__cachedBitmapBounds.y += this.__cachedFilterBounds.y;
				}
			}
		}
		if(!transformOnly) {
			if(!this.__worldColorTransform.__equals(this.get_transform().get_colorTransform())) this.__worldColorTransform = this.get_transform().get_colorTransform().__clone();
			if(this.parent != null) {
				this.__worldAlpha = this.get_alpha() * this.parent.__worldAlpha;
				this.__worldColorTransform.__combine(this.parent.__worldColorTransform);
				if(this.blendMode == null || this.blendMode == openfl_display_BlendMode.NORMAL) this.__blendMode = this.parent.__blendMode;
				if(this.shader == null) this.__shader = this.parent.__shader;
			} else this.__worldAlpha = this.get_alpha();
			if(updateChildren && this.__renderDirty) this.__renderDirty = false;
		}
	}
	,__updateChildren: function(transformOnly) {
		this.__renderable = this.get_visible() && this.get_scaleX() != 0 && this.get_scaleY() != 0 && !this.__isMask;
		if(!this.__renderable && !this.__isMask) return;
		this.__worldAlpha = this.get_alpha();
		if(this.__transformDirty) {
			this.__transformDirty = false;
			openfl_display_DisplayObject.__worldTransformDirty--;
		}
	}
	,__updateMask: function(maskGraphics) {
		if(this.__graphics != null) {
			maskGraphics.__commands.overrideMatrix(this.__worldTransform);
			maskGraphics.__commands.append(this.__graphics.__commands);
			maskGraphics.set___dirty(true);
			maskGraphics.__visible = true;
			if(maskGraphics.__bounds == null) maskGraphics.__bounds = new openfl_geom_Rectangle();
			this.__graphics.__getBounds(maskGraphics.__bounds,openfl_geom_Matrix.__identity);
		}
	}
	,get_alpha: function() {
		return this.__alpha;
	}
	,get_cacheAsBitmap: function() {
		return this.__cacheAsBitmap;
	}
	,get_filters: function() {
		if(this.__filters == null) return []; else return this.__filters.slice();
	}
	,set_filters: function(value) {
		if(value != null && value.length > 0) {
			this.__filters = value;
			this.__forceCacheAsBitmap = true;
			this.__cacheAsBitmap = true;
			this.__updateFilters = true;
		} else {
			this.__filters = null;
			this.__forceCacheAsBitmap = false;
			this.__cacheAsBitmap = false;
			this.__updateFilters = false;
		}
		if(!this.__renderDirty) {
			this.__updateCachedBitmap = true;
			this.__updateFilters = this.get_filters() != null && this.get_filters().length > 0;
			this.__renderDirty = true;
			openfl_display_DisplayObject.__worldRenderDirty++;
		}
		return value;
	}
	,get_height: function() {
		var bounds = new openfl_geom_Rectangle();
		this.__getBounds(bounds,this.__transform);
		return bounds.height;
	}
	,set_height: function(value) {
		var bounds = new openfl_geom_Rectangle();
		var matrix = openfl_geom_Matrix.__temp;
		matrix.identity();
		this.__getBounds(bounds,matrix);
		if(value != bounds.height) this.set_scaleY(value / bounds.height); else this.set_scaleY(1);
		return value;
	}
	,get_mask: function() {
		return this.__mask;
	}
	,get_mouseX: function() {
		var mouseX;
		if(this.stage != null) mouseX = this.stage.__mouseX; else mouseX = openfl_Lib.current.stage.__mouseX;
		var mouseY;
		if(this.stage != null) mouseY = this.stage.__mouseY; else mouseY = openfl_Lib.current.stage.__mouseY;
		return this.__getWorldTransform().__transformInverseX(mouseX,mouseY);
	}
	,get_mouseY: function() {
		var mouseX;
		if(this.stage != null) mouseX = this.stage.__mouseX; else mouseX = openfl_Lib.current.stage.__mouseX;
		var mouseY;
		if(this.stage != null) mouseY = this.stage.__mouseY; else mouseY = openfl_Lib.current.stage.__mouseY;
		return this.__getWorldTransform().__transformInverseY(mouseX,mouseY);
	}
	,get_name: function() {
		return this.__name;
	}
	,set_name: function(value) {
		return this.__name = value;
	}
	,get_rotation: function() {
		return this.__rotation;
	}
	,set_rotation: function(value) {
		if(value != this.__rotation) {
			this.__rotation = value;
			var radians = this.__rotation * (Math.PI / 180);
			this.__rotationSine = Math.sin(radians);
			this.__rotationCosine = Math.cos(radians);
			var __scaleX = this.get_scaleX();
			var __scaleY = this.get_scaleY();
			this.__transform.a = this.__rotationCosine * __scaleX;
			this.__transform.b = this.__rotationSine * __scaleX;
			this.__transform.c = -this.__rotationSine * __scaleY;
			this.__transform.d = this.__rotationCosine * __scaleY;
			if(!this.__transformDirty) {
				this.__transformDirty = true;
				openfl_display_DisplayObject.__worldTransformDirty++;
			}
		}
		return value;
	}
	,get_scaleX: function() {
		if(this.__transform.b == 0) return this.__transform.a; else return Math.sqrt(this.__transform.a * this.__transform.a + this.__transform.b * this.__transform.b);
	}
	,set_scaleX: function(value) {
		if(this.__transform.c == 0) {
			if(value != this.__transform.a) {
				if(!this.__transformDirty) {
					this.__transformDirty = true;
					openfl_display_DisplayObject.__worldTransformDirty++;
				}
			}
			this.__transform.a = value;
		} else {
			var a = this.__rotationCosine * value;
			var b = this.__rotationSine * value;
			if(this.__transform.a != a || this.__transform.b != b) {
				if(!this.__transformDirty) {
					this.__transformDirty = true;
					openfl_display_DisplayObject.__worldTransformDirty++;
				}
			}
			this.__transform.a = a;
			this.__transform.b = b;
		}
		return value;
	}
	,get_scaleY: function() {
		if(this.__transform.c == 0) return this.__transform.d; else return Math.sqrt(this.__transform.c * this.__transform.c + this.__transform.d * this.__transform.d);
	}
	,set_scaleY: function(value) {
		if(this.__transform.c == 0) {
			if(value != this.__transform.d) {
				if(!this.__transformDirty) {
					this.__transformDirty = true;
					openfl_display_DisplayObject.__worldTransformDirty++;
				}
			}
			this.__transform.d = value;
		} else {
			var c = -this.__rotationSine * value;
			var d = this.__rotationCosine * value;
			if(this.__transform.d != d || this.__transform.c != c) {
				if(!this.__transformDirty) {
					this.__transformDirty = true;
					openfl_display_DisplayObject.__worldTransformDirty++;
				}
			}
			this.__transform.c = c;
			this.__transform.d = d;
		}
		return value;
	}
	,get_scrollRect: function() {
		if(this.__scrollRect == null) return null;
		return this.__scrollRect.clone();
	}
	,set_scrollRect: function(value) {
		if(value != this.__scrollRect) {
			if(!this.__transformDirty) {
				this.__transformDirty = true;
				openfl_display_DisplayObject.__worldTransformDirty++;
			}
		}
		return this.__scrollRect = value;
	}
	,get_transform: function() {
		if(this.__objectTransform == null) this.__objectTransform = new openfl_geom_Transform(this);
		return this.__objectTransform;
	}
	,get_visible: function() {
		return this.__visible;
	}
	,set_visible: function(value) {
		if(value != this.__visible) {
			if(!this.__renderDirty) {
				this.__updateCachedBitmap = true;
				this.__updateFilters = this.get_filters() != null && this.get_filters().length > 0;
				this.__renderDirty = true;
				openfl_display_DisplayObject.__worldRenderDirty++;
			}
		}
		return this.__visible = value;
	}
	,get_width: function() {
		var bounds = new openfl_geom_Rectangle();
		this.__getBounds(bounds,this.__transform);
		return bounds.width;
	}
	,get_x: function() {
		return this.__transform.tx;
	}
	,set_x: function(value) {
		if(value != this.__transform.tx) {
			if(!this.__transformDirty) {
				this.__transformDirty = true;
				openfl_display_DisplayObject.__worldTransformDirty++;
			}
		}
		return this.__transform.tx = value;
	}
	,get_y: function() {
		return this.__transform.ty;
	}
	,set_y: function(value) {
		if(value != this.__transform.ty) {
			if(!this.__transformDirty) {
				this.__transformDirty = true;
				openfl_display_DisplayObject.__worldTransformDirty++;
			}
		}
		return this.__transform.ty = value;
	}
	,__class__: openfl_display_DisplayObject
	,__properties__: {get_mouseY:"get_mouseY",get_mouseX:"get_mouseX",set_y:"set_y",get_y:"get_y",set_x:"set_x",get_x:"get_x",get_width:"get_width",set_visible:"set_visible",get_visible:"get_visible",get_transform:"get_transform",set_scrollRect:"set_scrollRect",get_scrollRect:"get_scrollRect",set_scaleY:"set_scaleY",get_scaleY:"get_scaleY",set_scaleX:"set_scaleX",get_scaleX:"get_scaleX",set_rotation:"set_rotation",get_rotation:"get_rotation",set_name:"set_name",get_name:"get_name",get_mask:"get_mask",set_height:"set_height",get_height:"get_height",set_filters:"set_filters",get_filters:"get_filters",get_cacheAsBitmap:"get_cacheAsBitmap",get_alpha:"get_alpha"}
});
var openfl_display_InteractiveObject = function() {
	openfl_display_DisplayObject.call(this);
	this.doubleClickEnabled = false;
	this.mouseEnabled = true;
	this.needsSoftKeyboard = false;
	this.__tabEnabled = false;
	this.tabIndex = -1;
};
$hxClasses["openfl.display.InteractiveObject"] = openfl_display_InteractiveObject;
openfl_display_InteractiveObject.__name__ = true;
openfl_display_InteractiveObject.__super__ = openfl_display_DisplayObject;
openfl_display_InteractiveObject.prototype = $extend(openfl_display_DisplayObject.prototype,{
	__getInteractive: function(stack) {
		if(stack != null) {
			stack.push(this);
			if(this.parent != null) this.parent.__getInteractive(stack);
		}
		return true;
	}
	,__hitTest: function(x,y,shapeFlag,stack,interactiveOnly) {
		if(!this.get_visible() || this.__isMask || interactiveOnly && !this.mouseEnabled) return false;
		return openfl_display_DisplayObject.prototype.__hitTest.call(this,x,y,shapeFlag,stack,interactiveOnly);
	}
	,get_tabEnabled: function() {
		return this.__tabEnabled;
	}
	,__class__: openfl_display_InteractiveObject
	,__properties__: $extend(openfl_display_DisplayObject.prototype.__properties__,{get_tabEnabled:"get_tabEnabled"})
});
var openfl_display_DisplayObjectContainer = function() {
	openfl_display_InteractiveObject.call(this);
	this.mouseChildren = true;
	this.__children = [];
	this.__removedChildren = [];
};
$hxClasses["openfl.display.DisplayObjectContainer"] = openfl_display_DisplayObjectContainer;
openfl_display_DisplayObjectContainer.__name__ = true;
openfl_display_DisplayObjectContainer.__super__ = openfl_display_InteractiveObject;
openfl_display_DisplayObjectContainer.prototype = $extend(openfl_display_InteractiveObject.prototype,{
	addChild: function(child) {
		if(child != null) {
			if(child.parent != null) child.parent.removeChild(child);
			this.__children.push(child);
			child.parent = this;
			if(this.stage != null) child.__setStageReference(this.stage);
			if(!child.__transformDirty) {
				child.__transformDirty = true;
				openfl_display_DisplayObject.__worldTransformDirty++;
			}
			if(!child.__renderDirty) {
				child.__updateCachedBitmap = true;
				child.__updateFilters = child.get_filters() != null && child.get_filters().length > 0;
				child.__renderDirty = true;
				openfl_display_DisplayObject.__worldRenderDirty++;
			}
			if(!this.__renderDirty) {
				this.__updateCachedBitmap = true;
				this.__updateFilters = this.get_filters() != null && this.get_filters().length > 0;
				this.__renderDirty = true;
				openfl_display_DisplayObject.__worldRenderDirty++;
			}
			var event = new openfl_events_Event(openfl_events_Event.ADDED,true);
			event.target = child;
			child.__dispatchEvent(event);
		}
		return child;
	}
	,getChildAt: function(index) {
		if(index >= 0 && index < this.__children.length) return this.__children[index];
		return null;
	}
	,getObjectsUnderPoint: function(point) {
		var stack = [];
		this.__hitTest(point.x,point.y,false,stack,false);
		stack.reverse();
		return stack;
	}
	,removeChild: function(child) {
		if(child != null && child.parent == this) {
			child.__dispatchEvent(new openfl_events_Event(openfl_events_Event.REMOVED,true));
			if(this.stage != null) child.__setStageReference(null);
			child.parent = null;
			HxOverrides.remove(this.__children,child);
			this.__removedChildren.push(child);
			if(!child.__transformDirty) {
				child.__transformDirty = true;
				openfl_display_DisplayObject.__worldTransformDirty++;
			}
			if(!child.__renderDirty) {
				child.__updateCachedBitmap = true;
				child.__updateFilters = child.get_filters() != null && child.get_filters().length > 0;
				child.__renderDirty = true;
				openfl_display_DisplayObject.__worldRenderDirty++;
			}
			if(!this.__renderDirty) {
				this.__updateCachedBitmap = true;
				this.__updateFilters = this.get_filters() != null && this.get_filters().length > 0;
				this.__renderDirty = true;
				openfl_display_DisplayObject.__worldRenderDirty++;
			}
		}
		return child;
	}
	,removeChildAt: function(index) {
		if(index >= 0 && index < this.__children.length) return this.removeChild(this.__children[index]);
		return null;
	}
	,__broadcast: function(event,notifyChilden) {
		if(event.target == null) event.target = this;
		var result = openfl_display_InteractiveObject.prototype.__broadcast.call(this,event,notifyChilden);
		if(!event.__isCancelled && notifyChilden) {
			var _g = 0;
			var _g1 = this.__children;
			while(_g < _g1.length) {
				var child = _g1[_g];
				++_g;
				child.__broadcast(event,true);
				if(event.__isCancelled) return true;
			}
		}
		return result;
	}
	,__enterFrame: function(deltaTime) {
		var _g = 0;
		var _g1 = this.__children;
		while(_g < _g1.length) {
			var child = _g1[_g];
			++_g;
			child.__enterFrame(deltaTime);
		}
	}
	,__getBounds: function(rect,matrix) {
		openfl_display_InteractiveObject.prototype.__getBounds.call(this,rect,matrix);
		if(this.__children.length == 0) return;
		if(matrix != null) {
			this.__updateTransforms(matrix);
			this.__updateChildren(true);
		}
		var _g = 0;
		var _g1 = this.__children;
		while(_g < _g1.length) {
			var child = _g1[_g];
			++_g;
			if(child.get_scaleX() == 0 || child.get_scaleY() == 0 || child.__isMask) continue;
			child.__getBounds(rect,child.__worldTransform);
		}
		if(matrix != null) {
			this.__updateTransforms();
			this.__updateChildren(true);
		}
	}
	,__getRenderBounds: function(rect,matrix) {
		if(this.__scrollRect != null) {
			openfl_display_InteractiveObject.prototype.__getRenderBounds.call(this,rect,matrix);
			return;
		} else openfl_display_InteractiveObject.prototype.__getBounds.call(this,rect,matrix);
		if(this.__children.length == 0) return;
		if(matrix != null) {
			this.__updateTransforms(matrix);
			this.__updateChildren(true);
		}
		var _g = 0;
		var _g1 = this.__children;
		while(_g < _g1.length) {
			var child = _g1[_g];
			++_g;
			if(child.get_scaleX() == 0 || child.get_scaleY() == 0 || child.__isMask) continue;
			child.__getRenderBounds(rect,child.__worldTransform);
		}
		if(matrix != null) {
			this.__updateTransforms();
			this.__updateChildren(true);
		}
	}
	,__hitTest: function(x,y,shapeFlag,stack,interactiveOnly) {
		if(!this.get_visible() || this.__isMask || interactiveOnly && !this.mouseEnabled && !this.mouseChildren) return false;
		if(this.get_mask() != null && !this.get_mask().__hitTestMask(x,y)) return false;
		if(this.get_scrollRect() != null && !this.get_scrollRect().containsPoint(this.globalToLocal(new openfl_geom_Point(x,y)))) return false;
		var i = this.__children.length;
		if(interactiveOnly) {
			if(stack == null || !this.mouseChildren) {
				while(--i >= 0) if(this.__children[i].__hitTest(x,y,shapeFlag,null,true)) {
					if(stack != null) stack.push(this);
					return true;
				}
			} else if(stack != null) {
				var length = stack.length;
				var interactive = false;
				var hitTest = false;
				while(--i >= 0) {
					interactive = this.__children[i].__getInteractive(null);
					if(interactive || this.mouseEnabled && !hitTest) {
						if(this.__children[i].__hitTest(x,y,shapeFlag,stack,true)) {
							hitTest = true;
							if(interactive) break;
						}
					}
				}
				if(hitTest) {
					stack.splice(length,0,this);
					return true;
				}
			}
		} else while(--i >= 0) this.__children[i].__hitTest(x,y,shapeFlag,stack,false);
		return false;
	}
	,__hitTestMask: function(x,y) {
		var i = this.__children.length;
		while(--i >= 0) if(this.__children[i].__hitTestMask(x,y)) return true;
		return false;
	}
	,__renderCairo: function(renderSession) {
		if(!this.__renderable || this.__worldAlpha <= 0) return;
		openfl_display_InteractiveObject.prototype.__renderCairo.call(this,renderSession);
		if(this.get_scrollRect() != null) renderSession.maskManager.pushRect(this.get_scrollRect(),this.__worldTransform);
		if(this.__mask != null) renderSession.maskManager.pushMask(this.__mask);
		var _g = 0;
		var _g1 = this.__children;
		while(_g < _g1.length) {
			var child = _g1[_g];
			++_g;
			child.__renderCairo(renderSession);
		}
		if(this.__removedChildren.length > 0) this.__removedChildren.splice(0,this.__removedChildren.length);
		if(this.__mask != null) renderSession.maskManager.popMask();
		if(this.get_scrollRect() != null) renderSession.maskManager.popRect();
	}
	,__renderCairoMask: function(renderSession) {
		if(this.__graphics != null) openfl__$internal_renderer_cairo_CairoGraphics.renderMask(this.__graphics,renderSession);
		var _g = 0;
		var _g1 = this.__children;
		while(_g < _g1.length) {
			var child = _g1[_g];
			++_g;
			child.__renderCairoMask(renderSession);
		}
	}
	,__renderCanvas: function(renderSession) {
		if(!this.__renderable || this.__worldAlpha <= 0) return;
		openfl_display_InteractiveObject.prototype.__renderCanvas.call(this,renderSession);
		if(this.get_scrollRect() != null) renderSession.maskManager.pushRect(this.get_scrollRect(),this.__worldTransform);
		if(this.__mask != null) renderSession.maskManager.pushMask(this.__mask);
		var _g = 0;
		var _g1 = this.__children;
		while(_g < _g1.length) {
			var child = _g1[_g];
			++_g;
			child.__renderCanvas(renderSession);
		}
		if(this.__removedChildren.length > 0) this.__removedChildren.splice(0,this.__removedChildren.length);
		if(this.__mask != null) renderSession.maskManager.popMask();
		if(this.get_scrollRect() != null) renderSession.maskManager.popRect();
	}
	,__renderCanvasMask: function(renderSession) {
		if(this.__graphics != null) openfl__$internal_renderer_canvas_CanvasGraphics.renderMask(this.__graphics,renderSession);
		var bounds = new openfl_geom_Rectangle();
		this.__getBounds(bounds,this.__transform);
		renderSession.context.rect(0,0,bounds.width,bounds.height);
	}
	,__renderDOM: function(renderSession) {
		openfl_display_InteractiveObject.prototype.__renderDOM.call(this,renderSession);
		if(this.__mask != null) renderSession.maskManager.pushMask(this.__mask);
		var _g = 0;
		var _g1 = this.__children;
		while(_g < _g1.length) {
			var child = _g1[_g];
			++_g;
			child.__renderDOM(renderSession);
		}
		var _g2 = 0;
		var _g11 = this.__removedChildren;
		while(_g2 < _g11.length) {
			var orphan = _g11[_g2];
			++_g2;
			if(orphan.stage == null) orphan.__renderDOM(renderSession);
		}
		if(this.__removedChildren.length > 0) this.__removedChildren.splice(0,this.__removedChildren.length);
		if(this.__mask != null) renderSession.maskManager.popMask();
	}
	,__renderGL: function(renderSession) {
		if(!this.__renderable || this.__worldAlpha <= 0) return;
		if(this.__cacheAsBitmap) {
			this.__cacheGL(renderSession);
			return;
		}
		if(this.__scrollRect != null) renderSession.maskManager.pushRect(this.__scrollRect,this.__renderTransform);
		if(this.__mask != null && this.__maskGraphics != null && this.__maskGraphics.__commands.get_length() > 0) renderSession.maskManager.pushMask(this);
		if(this.__graphics != null) {
			if(this.__graphics.__hardware) openfl__$internal_renderer_opengl_utils_GraphicsRenderer.render(this,renderSession); else {
				openfl__$internal_renderer_canvas_CanvasGraphics.render(this.__graphics,renderSession);
				openfl__$internal_renderer_opengl_GLRenderer.renderBitmap(this,renderSession);
			}
		}
		var _g = 0;
		var _g1 = this.__children;
		while(_g < _g1.length) {
			var child = _g1[_g];
			++_g;
			child.__renderGL(renderSession);
		}
		if(this.__mask != null && this.__maskGraphics != null && this.__maskGraphics.__commands.get_length() > 0) renderSession.maskManager.popMask();
		if(this.__scrollRect != null) renderSession.maskManager.popRect();
		if(this.__removedChildren.length > 0) this.__removedChildren.splice(0,this.__removedChildren.length);
	}
	,__setStageReference: function(stage) {
		if(this.stage != stage) {
			if(this.stage != null) this.__dispatchEvent(new openfl_events_Event(openfl_events_Event.REMOVED_FROM_STAGE,false,false));
			this.stage = stage;
			if(stage != null) this.__dispatchEvent(new openfl_events_Event(openfl_events_Event.ADDED_TO_STAGE,false,false));
			if(this.__children != null) {
				var _g = 0;
				var _g1 = this.__children;
				while(_g < _g1.length) {
					var child = _g1[_g];
					++_g;
					child.__setStageReference(stage);
				}
			}
		}
	}
	,__update: function(transformOnly,updateChildren,maskGraphics) {
		openfl_display_InteractiveObject.prototype.__update.call(this,transformOnly,updateChildren,maskGraphics);
		if(!this.__renderable && !this.__isMask) return;
		if(updateChildren) {
			var _g = 0;
			var _g1 = this.__children;
			while(_g < _g1.length) {
				var child = _g1[_g];
				++_g;
				child.__update(transformOnly,true,maskGraphics);
			}
		}
	}
	,__updateChildren: function(transformOnly) {
		openfl_display_InteractiveObject.prototype.__updateChildren.call(this,transformOnly);
		var _g = 0;
		var _g1 = this.__children;
		while(_g < _g1.length) {
			var child = _g1[_g];
			++_g;
			child.__update(transformOnly,true);
		}
	}
	,get_numChildren: function() {
		return this.__children.length;
	}
	,__class__: openfl_display_DisplayObjectContainer
	,__properties__: $extend(openfl_display_InteractiveObject.prototype.__properties__,{get_numChildren:"get_numChildren"})
});
var openfl_display_Sprite = function() {
	openfl_display_DisplayObjectContainer.call(this);
	this.buttonMode = false;
	this.useHandCursor = true;
	this.loaderInfo = openfl_display_LoaderInfo.create(null);
};
$hxClasses["openfl.display.Sprite"] = openfl_display_Sprite;
openfl_display_Sprite.__name__ = true;
openfl_display_Sprite.__super__ = openfl_display_DisplayObjectContainer;
openfl_display_Sprite.prototype = $extend(openfl_display_DisplayObjectContainer.prototype,{
	startDrag: function(lockCenter,bounds) {
		if(lockCenter == null) lockCenter = false;
		if(this.stage != null) this.stage.__startDrag(this,lockCenter,bounds);
	}
	,stopDrag: function() {
		if(this.stage != null) this.stage.__stopDrag(this);
	}
	,__getCursor: function() {
		if(this.buttonMode && this.useHandCursor) return lime_ui_MouseCursor.POINTER; else return null;
	}
	,__hitTest: function(x,y,shapeFlag,stack,interactiveOnly) {
		if(!this.get_visible() || this.__isMask || interactiveOnly && !this.mouseEnabled && !this.mouseChildren) return false;
		if(this.get_mask() != null && !this.get_mask().__hitTestMask(x,y)) return false;
		if(openfl_display_DisplayObjectContainer.prototype.__hitTest.call(this,x,y,shapeFlag,stack,interactiveOnly)) return interactiveOnly; else if((!interactiveOnly || this.mouseEnabled) && this.__graphics != null && this.__graphics.__hitTest(x,y,shapeFlag,this.__getWorldTransform())) {
			if(stack != null) stack.push(this);
			return true;
		}
		return false;
	}
	,__hitTestMask: function(x,y) {
		if(openfl_display_DisplayObjectContainer.prototype.__hitTestMask.call(this,x,y)) return true; else if(this.__graphics != null && this.__graphics.__hitTest(x,y,true,this.__getWorldTransform())) return true;
		return false;
	}
	,get_graphics: function() {
		if(this.__graphics == null) {
			this.__graphics = new openfl_display_Graphics();
			this.__graphics.__owner = this;
		}
		return this.__graphics;
	}
	,get_tabEnabled: function() {
		return this.__tabEnabled || this.buttonMode;
	}
	,__class__: openfl_display_Sprite
	,__properties__: $extend(openfl_display_DisplayObjectContainer.prototype.__properties__,{get_graphics:"get_graphics"})
});
var openfl_geom_Point = function(x,y) {
	if(y == null) y = 0;
	if(x == null) x = 0;
	this.x = x;
	this.y = y;
};
$hxClasses["openfl.geom.Point"] = openfl_geom_Point;
openfl_geom_Point.__name__ = true;
openfl_geom_Point.prototype = {
	clone: function() {
		return new openfl_geom_Point(this.x,this.y);
	}
	,copyFrom: function(sourcePoint) {
		this.x = sourcePoint.x;
		this.y = sourcePoint.y;
	}
	,offset: function(dx,dy) {
		this.x += dx;
		this.y += dy;
	}
	,setTo: function(xa,ya) {
		this.x = xa;
		this.y = ya;
	}
	,toString: function() {
		return "(x=" + this.x + ", y=" + this.y + ")";
	}
	,__class__: openfl_geom_Point
};
var ru_octasoft_oem_designer_Main = $hx_exports.ru.octasoft.oem.designer.Main = function() {
	var _g = this;
	openfl_display_Sprite.call(this);
	ru_octasoft_oem_designer_Main.messages = new ru_octasoft_oem_designer_Messages(null);
	ru_octasoft_oem_designer_Main.view = this;
	ru_octasoft_oem_designer_Main.undo = new List();
	ru_octasoft_oem_designer_Main.redo = new List();
	ru_octasoft_oem_designer_Main.toolbar = new ru_octasoft_oem_designer_Toolbar();
	this.addChild(ru_octasoft_oem_designer_Main.toolbar);
	this.set_name("main");
	ru_octasoft_oem_designer_Main.cont = new openfl_display_Sprite();
	ru_octasoft_oem_designer_Main.cont.set_name("cont");
	ru_octasoft_oem_designer_Main.cont.set_x(6);
	ru_octasoft_oem_designer_Main.cont.set_y(ru_octasoft_oem_designer_Main.toolbar.get_height() + ru_octasoft_oem_designer_Main.GRID_PADDING);
	ru_octasoft_oem_designer_Main.cont.set_scrollRect(new openfl_geom_Rectangle(-6,-6,ru_octasoft_oem_designer_Main.clipSize + 12,ru_octasoft_oem_designer_Main.clipSize + 12));
	this.addChild(ru_octasoft_oem_designer_Main.cont);
	ru_octasoft_oem_designer_Main.grid = new ru_octasoft_oem_designer_Grid();
	ru_octasoft_oem_designer_Main.gridScale = ru_octasoft_oem_designer_Main.grid.scale;
	ru_octasoft_oem_designer_Main.cont.addChild(ru_octasoft_oem_designer_Main.grid);
	ru_octasoft_oem_designer_Main.cont.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,function(e) {
		ru_octasoft_oem_designer_Main.click.x = e.stageX;
		ru_octasoft_oem_designer_Main.click.y = e.stageY;
		if(!ru_octasoft_oem_designer_Main.draggable) return;
		motion_Actuate.stop(ru_octasoft_oem_designer_Main.grid);
		ru_octasoft_oem_designer_Main.grid.startDrag();
	});
	ru_octasoft_oem_designer_Main.cont.addEventListener(openfl_events_MouseEvent.MOUSE_MOVE,function(e1) {
		if(ru_octasoft_oem_designer_Main.gridDown && !ru_octasoft_oem_designer_Main.dragged) ru_octasoft_oem_designer_Main.dragged = true;
	});
	ru_octasoft_oem_designer_Main.cont.addEventListener(openfl_events_MouseEvent.MOUSE_UP,function(e2) {
		if(ru_octasoft_oem_designer_Main.click.x == e2.stageX && ru_octasoft_oem_designer_Main.click.y == e2.stageY) {
			var point = new openfl_geom_Point(e2.stageX,e2.stageY);
			var objects = _g.stage.getObjectsUnderPoint(point);
			if(objects.length == 1 && ru_octasoft_oem_designer_Main.selected != null && ru_octasoft_oem_designer_Main.selected.get_selected()) {
				ru_octasoft_oem_designer_Main.selected.set_selected(false);
				ru_octasoft_oem_designer_Main.selected = null;
				_g.showPopup(ru_octasoft_oem_designer_Main.selected,false);
			}
		}
		ru_octasoft_oem_designer_Main.dragged = false;
		ru_octasoft_oem_designer_Main.grid.stopDrag();
		ru_octasoft_oem_designer_Main.validateEditor();
		ru_octasoft_oem_designer_Main.gridDown = false;
	});
	ru_octasoft_oem_designer_Main.cont.addEventListener(openfl_events_MouseEvent.MOUSE_OUT,function(e3) {
		ru_octasoft_oem_designer_Main.stopDragging();
	});
	ru_octasoft_oem_designer_Main.toolbar.addEventListener(ru_octasoft_oem_designer_events_ZoomEvent.ZOOM_IN,function(e4) {
		ru_octasoft_oem_designer_Main.shelfPopup.set_visible(false);
		ru_octasoft_oem_designer_Main.grid.zoomIn();
		ru_octasoft_oem_designer_Main.validateEditor();
	});
	ru_octasoft_oem_designer_Main.toolbar.addEventListener(ru_octasoft_oem_designer_events_ZoomEvent.ZOOM_OUT,function(e5) {
		ru_octasoft_oem_designer_Main.shelfPopup.set_visible(false);
		ru_octasoft_oem_designer_Main.grid.zoomOut();
		ru_octasoft_oem_designer_Main.validateEditor();
	});
	ru_octasoft_oem_designer_Main.toolbar.addEventListener(ru_octasoft_oem_designer_events_ClearEvent.NAME,function(e6) {
		ru_octasoft_oem_designer_Main.reset(false);
	});
	ru_octasoft_oem_designer_Main.toolbar.addEventListener(ru_octasoft_oem_designer_events_DeleteEvent.NAME,function(e7) {
		if(ru_octasoft_oem_designer_Main.selected != null) {
			ru_octasoft_oem_designer_Main.grid.dispatchEvent(new ru_octasoft_oem_designer_events_FigureDeletedEvent(ru_octasoft_oem_designer_Main.selected,new openfl_geom_Point(ru_octasoft_oem_designer_Main.selected.get_x(),ru_octasoft_oem_designer_Main.selected.get_y()),ru_octasoft_oem_designer_events_EventContext.DIRECT));
			ru_octasoft_oem_designer_Main.selected = null;
		}
	});
	ru_octasoft_oem_designer_Main.toolbar.addEventListener(ru_octasoft_oem_designer_events_UndoEvent.NAME,$bind(this,this.onUndo));
	ru_octasoft_oem_designer_Main.toolbar.addEventListener(ru_octasoft_oem_designer_events_RedoEvent.NAME,$bind(this,this.onRedo));
	var rightColumnOffsetX = this.stage.stageWidth - ru_octasoft_oem_designer_Main.RIGHTCOL_WIDTH;
	var rightColW = ru_octasoft_oem_designer_Main.RIGHTCOL_WIDTH;
	ru_octasoft_oem_designer_Main.rightTitle = new openfl_display_Sprite();
	ru_octasoft_oem_designer_Main.rightTitle.set_x(rightColumnOffsetX);
	this.addChild(ru_octasoft_oem_designer_Main.rightTitle);
	ru_octasoft_oem_designer_Main.rightTitle.get_graphics().beginBitmapFill(openfl_Assets.getBitmapData("assets/images/border2.png"));
	ru_octasoft_oem_designer_Main.rightTitle.get_graphics().drawRect(0.,0.,rightColW,ru_octasoft_oem_designer_Main.TOOLBAR_HEIGHT);
	ru_octasoft_oem_designer_Main.rightTitle.get_graphics().endFill();
	var font = openfl_Assets.getFont(ru_octasoft_oem_designer_Main.FONT_BOLD);
	var defaultFormat = new openfl_text_TextFormat(font.name,21,0);
	defaultFormat.align = openfl_text_TextFormatAlign.LEFT;
	ru_octasoft_oem_designer_Main.label = new openfl_text_TextField();
	ru_octasoft_oem_designer_Main.label.set_defaultTextFormat(defaultFormat);
	ru_octasoft_oem_designer_Main.label.set_embedFonts(true);
	ru_octasoft_oem_designer_Main.label.set_autoSize(openfl_text_TextFieldAutoSize.CENTER);
	ru_octasoft_oem_designer_Main.label.set_text(ru_octasoft_oem_designer_Main.messages.rightColumnLabel);
	ru_octasoft_oem_designer_Main.label.set_x(rightColumnOffsetX + rightColW / 2 - ru_octasoft_oem_designer_Main.label.get_width() / 2);
	ru_octasoft_oem_designer_Main.label.set_y(ru_octasoft_oem_designer_Main.TOOLBAR_HEIGHT / 2 - ru_octasoft_oem_designer_Main.label.get_height() / 2);
	this.addChild(ru_octasoft_oem_designer_Main.label);
	ru_octasoft_oem_designer_Main.eq = new ru_octasoft_oem_designer_Equipment(ru_octasoft_oem_designer_Main.grid);
	ru_octasoft_oem_designer_Main.eq.set_x(rightColumnOffsetX);
	ru_octasoft_oem_designer_Main.eq.set_y(ru_octasoft_oem_designer_Main.TOOLBAR_HEIGHT + 30);
	this.addChild(ru_octasoft_oem_designer_Main.eq);
	ru_octasoft_oem_designer_Main.grid.addEventListener(ru_octasoft_oem_designer_events_FigureDeletedEvent.NAME,function(e8) {
		console.log("FigureDeletedEvent" + Std.string(e8.from));
		ru_octasoft_oem_designer_Main.shelfPopup.set_visible(false);
		var figure = e8.figure;
		var point1 = e8.from;
		ru_octasoft_oem_designer_Main.grid.removeChild(figure);
		figure.set_selected(false);
		var _g1 = 0;
		var _g2 = ru_octasoft_oem_designer_Main.eq.body.get_numChildren();
		while(_g1 < _g2) {
			var i = _g1++;
			var ch;
			ch = js_Boot.__cast(ru_octasoft_oem_designer_Main.eq.body.getChildAt(i) , ru_octasoft_oem_designer_Item);
			if(ch.figure.id == e8.figure.id) {
				var _g21 = ch;
				var _g3 = _g21.usedQty;
				_g21.set_usedQty(_g3 - 1);
				_g3;
			}
		}
		var _g4 = e8.context;
		switch(_g4[1]) {
		case 1:
			ru_octasoft_oem_designer_Main.redo.push(new ru_octasoft_oem_designer_events_FigureAddedEvent(figure,point1,ru_octasoft_oem_designer_events_EventContext.REDO));
			break;
		case 2:
			ru_octasoft_oem_designer_Main.undo.push(new ru_octasoft_oem_designer_events_FigureAddedEvent(figure,point1,ru_octasoft_oem_designer_events_EventContext.UNDO));
			break;
		default:
			ru_octasoft_oem_designer_Main.redo.clear();
			ru_octasoft_oem_designer_Main.undo.push(new ru_octasoft_oem_designer_events_FigureAddedEvent(figure,point1,ru_octasoft_oem_designer_events_EventContext.UNDO));
		}
	});
	ru_octasoft_oem_designer_Main.grid.addEventListener(ru_octasoft_oem_designer_events_FigureAddedEvent.NAME,function(e9) {
		console.log("FigureAddedEvent " + Std.string(e9.to));
		var figure1 = e9.figure;
		var point2 = e9.to;
		figure1.set_x(point2.x);
		figure1.set_y(point2.y);
		ru_octasoft_oem_designer_Main.grid.addChild(figure1);
		if(e9.shift) {
			var bs = figure1.picture.getBounds(_g);
			var _g11 = figure1;
			_g11.set_x(_g11.get_x() + bs.width / 2.);
			var _g12 = figure1;
			_g12.set_y(_g12.get_y() + bs.height / 2.);
		}
		if(!_g.validateDrop(figure1)) {
			ru_octasoft_oem_designer_Main.grid.removeChild(figure1);
			return;
		}
		_g.validatePosition(figure1);
		_g.validateBounds(figure1);
		var _g22 = 0;
		var _g13 = ru_octasoft_oem_designer_Main.eq.body.get_numChildren();
		while(_g22 < _g13) {
			var i1 = _g22++;
			var ch1;
			ch1 = js_Boot.__cast(ru_octasoft_oem_designer_Main.eq.body.getChildAt(i1) , ru_octasoft_oem_designer_Item);
			if(ch1.figure.id == e9.figure.id) {
				var _g31 = ch1;
				var _g41 = _g31.usedQty;
				_g31.set_usedQty(_g41 + 1);
				_g41;
			}
		}
		var _g14 = e9.context;
		switch(_g14[1]) {
		case 1:
			ru_octasoft_oem_designer_Main.redo.push(new ru_octasoft_oem_designer_events_FigureDeletedEvent(figure1,point2,ru_octasoft_oem_designer_events_EventContext.REDO));
			break;
		case 2:
			ru_octasoft_oem_designer_Main.undo.push(new ru_octasoft_oem_designer_events_FigureDeletedEvent(figure1,point2,ru_octasoft_oem_designer_events_EventContext.UNDO));
			break;
		default:
			ru_octasoft_oem_designer_Main.redo.clear();
			ru_octasoft_oem_designer_Main.undo.push(new ru_octasoft_oem_designer_events_FigureDeletedEvent(figure1,point2,ru_octasoft_oem_designer_events_EventContext.UNDO));
		}
		_g.showPopup(figure1,true);
	});
	ru_octasoft_oem_designer_Main.grid.addEventListener(ru_octasoft_oem_designer_events_FigureMovedEvent.NAME,function(e10) {
		console.log("FigureMovedEvent" + Std.string(e10.from) + " -> " + Std.string(e10.to));
		var figure2 = e10.figure;
		var point3 = e10.to;
		figure2.set_x(point3.x);
		figure2.set_y(point3.y);
		if(!_g.validateDrop(figure2)) {
			figure2.set_x(e10.from.x);
			figure2.set_y(e10.from.y);
			return;
		}
		_g.validatePosition(figure2);
		_g.validateBounds(figure2);
		ru_octasoft_oem_designer_Main.grid.addChild(figure2);
		var _g15 = e10.context;
		switch(_g15[1]) {
		case 1:
			ru_octasoft_oem_designer_Main.redo.push(new ru_octasoft_oem_designer_events_FigureMovedEvent(figure2,e10.to,e10.from,ru_octasoft_oem_designer_events_EventContext.REDO));
			break;
		case 2:
			ru_octasoft_oem_designer_Main.undo.push(new ru_octasoft_oem_designer_events_FigureMovedEvent(figure2,e10.to,e10.from,ru_octasoft_oem_designer_events_EventContext.UNDO));
			break;
		default:
			ru_octasoft_oem_designer_Main.redo.clear();
			ru_octasoft_oem_designer_Main.undo.push(new ru_octasoft_oem_designer_events_FigureMovedEvent(figure2,e10.to,e10.from,ru_octasoft_oem_designer_events_EventContext.UNDO));
		}
	});
	ru_octasoft_oem_designer_Main.grid.addEventListener(ru_octasoft_oem_designer_events_FigureRotatedEvent.NAME,function(e11) {
		console.log("FigureRotateEvent " + e11.from + " " + e11.to);
		var figure3 = e11.figure;
		figure3.set_rotation(e11.to);
		var _g5 = e11.context;
		switch(_g5[1]) {
		case 1:
			ru_octasoft_oem_designer_Main.redo.push(new ru_octasoft_oem_designer_events_FigureRotatedEvent(figure3,e11.to,e11.from,ru_octasoft_oem_designer_events_EventContext.REDO));
			break;
		case 2:
			ru_octasoft_oem_designer_Main.undo.push(new ru_octasoft_oem_designer_events_FigureRotatedEvent(figure3,e11.to,e11.from,ru_octasoft_oem_designer_events_EventContext.UNDO));
			break;
		default:
			ru_octasoft_oem_designer_Main.redo.clear();
			ru_octasoft_oem_designer_Main.undo.push(new ru_octasoft_oem_designer_events_FigureRotatedEvent(figure3,e11.to,e11.from,ru_octasoft_oem_designer_events_EventContext.UNDO));
		}
	});
	ru_octasoft_oem_designer_Main.grid.addEventListener(ru_octasoft_oem_designer_events_FigureDraggedEvent.NAME,function(e12) {
		_g.validateBounds(e12.figure);
	});
	ru_octasoft_oem_designer_Main.grid.addEventListener(ru_octasoft_oem_designer_events_FigureSelectedEvent.NAME,$bind(this,this.onSelect));
	this.stage.addEventListener(openfl_events_KeyboardEvent.KEY_UP,function(e13) {
		var _g6 = e13.keyCode;
		switch(_g6) {
		case 37:
			_g.keyboardNav(false);
			break;
		case 39:
			_g.keyboardNav(true);
			break;
		}
	});
	ru_octasoft_oem_designer_Main.shelfPopup = new ru_octasoft_oem_designer_ShelfPopup();
	ru_octasoft_oem_designer_Main.cont.addChild(ru_octasoft_oem_designer_Main.shelfPopup);
	window.onEditorReady();
};
$hxClasses["ru.octasoft.oem.designer.Main"] = ru_octasoft_oem_designer_Main;
ru_octasoft_oem_designer_Main.__name__ = true;
ru_octasoft_oem_designer_Main.messages = null;
ru_octasoft_oem_designer_Main.view = null;
ru_octasoft_oem_designer_Main.rightTitle = null;
ru_octasoft_oem_designer_Main.label = null;
ru_octasoft_oem_designer_Main.toolbar = null;
ru_octasoft_oem_designer_Main.cont = null;
ru_octasoft_oem_designer_Main.grid = null;
ru_octasoft_oem_designer_Main.eq = null;
ru_octasoft_oem_designer_Main.dragged = null;
ru_octasoft_oem_designer_Main.gridDown = null;
ru_octasoft_oem_designer_Main.shelfPopup = null;
ru_octasoft_oem_designer_Main.selected = null;
ru_octasoft_oem_designer_Main.undo = null;
ru_octasoft_oem_designer_Main.redo = null;
ru_octasoft_oem_designer_Main.gridScale = null;
ru_octasoft_oem_designer_Main.compare2 = function(f1,f2,delta) {
	return Math.abs(f1 - f2) <= delta;
};
ru_octasoft_oem_designer_Main.compare = function(f1,f2) {
	return ru_octasoft_oem_designer_Main.compare2(f1,f2,0.5);
};
ru_octasoft_oem_designer_Main.validateEditor = function() {
	if(ru_octasoft_oem_designer_Main.grid.get_x() > 0) motion_Actuate.tween(ru_octasoft_oem_designer_Main.grid,1,{ x : 0});
	if(ru_octasoft_oem_designer_Main.grid.get_x() + ru_octasoft_oem_designer_Main.grid.scaledW <= ru_octasoft_oem_designer_Main.clipSize && ru_octasoft_oem_designer_Main.grid.scaledW >= ru_octasoft_oem_designer_Main.clipSize) motion_Actuate.tween(ru_octasoft_oem_designer_Main.grid,1,{ x : ru_octasoft_oem_designer_Main.clipSize - ru_octasoft_oem_designer_Main.grid.scaledW});
	if(ru_octasoft_oem_designer_Main.grid.get_y() > 0) motion_Actuate.tween(ru_octasoft_oem_designer_Main.grid,1,{ y : 0});
};
ru_octasoft_oem_designer_Main.stopDragging = function() {
	if(!ru_octasoft_oem_designer_Main.dragged) return;
	ru_octasoft_oem_designer_Main.dragged = false;
	ru_octasoft_oem_designer_Main.grid.stopDrag();
	ru_octasoft_oem_designer_Main.validateEditor();
};
ru_octasoft_oem_designer_Main.updateLanguage = function() {
	ru_octasoft_oem_designer_Main.label.set_text(ru_octasoft_oem_designer_Main.messages.rightColumnLabel);
	ru_octasoft_oem_designer_Main.shelfPopup.updateLanguage();
};
ru_octasoft_oem_designer_Main.init = function(config) {
	ru_octasoft_oem_designer_Main.reset();
	if(config.hideControls) {
		ru_octasoft_oem_designer_Main.eq.set_visible(ru_octasoft_oem_designer_Main.toolbar.set_visible(ru_octasoft_oem_designer_Main.label.set_visible(ru_octasoft_oem_designer_Main.rightTitle.set_visible(false))));
		ru_octasoft_oem_designer_Main.cont.set_scrollRect(null);
		ru_octasoft_oem_designer_Main.cont.set_y(6);
		ru_octasoft_oem_designer_Main.draggable = false;
	}
	if(config.language) {
		ru_octasoft_oem_designer_Main.messages = new ru_octasoft_oem_designer_Messages(config.language);
		ru_octasoft_oem_designer_Main.updateLanguage();
	}
	ru_octasoft_oem_designer_Main.grid.init(config.w,config.h,Type.createEnum(ru_octasoft_oem_designer_GridType,config.type),ru_octasoft_oem_designer_Main.clipSize);
	var tmp = Lambda.array(config.items);
	ru_octasoft_oem_designer_Main.load(tmp,function() {
		if(config.placedItems != null) {
			var tmp1 = config.placedItems;
			var _g = 0;
			while(_g < tmp1.length) {
				var o = tmp1[_g];
				++_g;
				var item = ru_octasoft_oem_designer_Main.eq.findItemById(o.id);
				if(item != null) ru_octasoft_oem_designer_Main.grid.add(item,o.x,o.y,o.rotation,o.lift);
			}
		}
	});
};
ru_octasoft_oem_designer_Main.load = function(queue,onComplete) {
	if(queue.length == 0) {
		onComplete();
		return;
	}
	var item = queue.shift();
	ru_octasoft_oem_designer_Main.eq.addObject(item,function() {
		ru_octasoft_oem_designer_Main.load(queue,onComplete);
	});
};
ru_octasoft_oem_designer_Main.scroll = function(t1,t2,current) {
	if(ru_octasoft_oem_designer_Main.prev == 0.) ru_octasoft_oem_designer_Main.prev = t1;
	if(t1 == t2) return;
	var delta = ru_octasoft_oem_designer_Main.prev - current;
	if(current < t1 && delta > 0) current = t1;
	if(current > t2 && delta < 0) current = t2;
	var k = (current - t1) / (t2 - t1);
	ru_octasoft_oem_designer_Main.view.set_y((t2 - t1) * k);
	ru_octasoft_oem_designer_Main.eq.scroll(k);
	ru_octasoft_oem_designer_Main.prev = current;
};
ru_octasoft_oem_designer_Main.reset = function(clearEquipment) {
	if(clearEquipment == null) clearEquipment = true;
	while(ru_octasoft_oem_designer_Main.grid.get_numChildren() > 0) ru_octasoft_oem_designer_Main.grid.removeChildAt(0);
	if(clearEquipment) {
		while(ru_octasoft_oem_designer_Main.eq.body.get_numChildren() > 0) ru_octasoft_oem_designer_Main.eq.body.removeChildAt(0);
		ru_octasoft_oem_designer_Main.eq.items = [];
		ru_octasoft_oem_designer_Main.eq.offY = 0.;
		ru_octasoft_oem_designer_Main.eq.body.set_y(0);
		ru_octasoft_oem_designer_Main.eq.body.set_height(0);
	}
	var _g1 = 0;
	var _g = ru_octasoft_oem_designer_Main.eq.body.get_numChildren();
	while(_g1 < _g) {
		var i = _g1++;
		var ch;
		ch = js_Boot.__cast(ru_octasoft_oem_designer_Main.eq.body.getChildAt(i) , ru_octasoft_oem_designer_Item);
		ch.set_usedQty(0);
	}
	ru_octasoft_oem_designer_Main.undo.clear();
	ru_octasoft_oem_designer_Main.redo.clear();
};
ru_octasoft_oem_designer_Main.placeItem = function(o) {
	var item = ru_octasoft_oem_designer_Main.eq.findItemById(o.id);
	if(item != null) ru_octasoft_oem_designer_Main.grid.add(item,o.x,o.y,o.rotation,o.lift);
};
ru_octasoft_oem_designer_Main.saveJPG = function() {
	var showPopup = ru_octasoft_oem_designer_Main.shelfPopup.get_visible();
	ru_octasoft_oem_designer_Main.shelfPopup.set_visible(false);
	if(ru_octasoft_oem_designer_Main.selected != null) ru_octasoft_oem_designer_Main.selected.set_selected(false);
	var scale = ru_octasoft_oem_designer_Main.grid.scale;
	ru_octasoft_oem_designer_Main.grid.setScale(ru_octasoft_oem_designer_Main.gridScale);
	var source = new openfl_display_BitmapData(Std["int"](ru_octasoft_oem_designer_Main.grid.get_width()) - 6,Std["int"](ru_octasoft_oem_designer_Main.grid.get_height()) - 6,false,16777215);
	source.draw(ru_octasoft_oem_designer_Main.grid,new openfl_geom_Matrix(1,0,0,1,6,6));
	var ba = source.getPixels(new openfl_geom_Rectangle(0,0,source.width + 6,source.height + 6));
	var bytes = haxe_io_Bytes.alloc(ba.length);
	var _g1 = 0;
	var _g = ba.length;
	while(_g1 < _g) {
		var a = _g1++;
		bytes.set(a,ba.readByte());
	}
	var out = new haxe_io_BytesOutput();
	var d = { width : source.width, height : source.height, quality : 100., pixels : bytes};
	new ru_octasoft_oem_designer_jpg_Writer(out).write(d);
	ru_octasoft_oem_designer_Main.grid.setScale(scale);
	ru_octasoft_oem_designer_Main.shelfPopup.set_visible(showPopup);
	if(ru_octasoft_oem_designer_Main.selected != null) ru_octasoft_oem_designer_Main.selected.set_selected(true);
	return haxe_crypto_Base64.encode(out.getBytes());
};
ru_octasoft_oem_designer_Main.getScene = function() {
	var scene = { objects : []};
	var _g1 = 0;
	var _g = ru_octasoft_oem_designer_Main.grid.get_numChildren();
	while(_g1 < _g) {
		var i = _g1++;
		var elem = ru_octasoft_oem_designer_Main.grid.getChildAt(i);
		if(!js_Boot.__instanceof(elem,ru_octasoft_oem_designer_figures_Figure)) continue;
		var figure = elem;
		var b1 = figure.picture.getBounds(ru_octasoft_oem_designer_Main.grid);
		var tx = figure.get_x() - b1.width / 2;
		var ty = figure.get_y() - b1.height / 2;
		var entry = { id : figure.id, x : Math.max(0,ru_octasoft_oem_designer_SizeUtil.pixelsToUnits(tx / ru_octasoft_oem_designer_Main.grid.scale)), y : Math.max(0,ru_octasoft_oem_designer_SizeUtil.pixelsToUnits(ty / ru_octasoft_oem_designer_Main.grid.scale)), rotation : figure.get_rotation()};
		scene.objects.push(entry);
		if(figure.type == ru_octasoft_oem_designer_ItemType.shelf) Reflect.setField(entry,"lift",figure.get_lift());
	}
	return scene;
};
ru_octasoft_oem_designer_Main.__super__ = openfl_display_Sprite;
ru_octasoft_oem_designer_Main.prototype = $extend(openfl_display_Sprite.prototype,{
	keyboardNav: function(next) {
		if(ru_octasoft_oem_designer_Main.selected == null || ru_octasoft_oem_designer_Main.selected.type != ru_octasoft_oem_designer_ItemType.shelf) return;
		var items = [];
		var _g1 = 0;
		var _g = ru_octasoft_oem_designer_Main.grid.get_numChildren();
		while(_g1 < _g) {
			var i = _g1++;
			var elem = ru_octasoft_oem_designer_Main.grid.getChildAt(i);
			if(!js_Boot.__instanceof(elem,ru_octasoft_oem_designer_figures_Figure)) continue;
			var figure = elem;
			if(figure.type != ru_octasoft_oem_designer_Main.selected.type) continue;
			items.push(figure);
		}
		if(items.length <= 1) return;
		items.sort(function(t1,t2) {
			return t1.number - t2.number;
		});
		var idx = HxOverrides.indexOf(items,ru_octasoft_oem_designer_Main.selected,0);
		if(next) {
			idx++;
			if(idx == items.length) idx = 0;
		} else {
			idx--;
			if(idx == -1) idx = items.length - 1;
		}
		ru_octasoft_oem_designer_Main.selected.set_selected(false);
		ru_octasoft_oem_designer_Main.selected = items[idx];
		ru_octasoft_oem_designer_Main.selected.set_selected(true);
		ru_octasoft_oem_designer_Main.selected.parent.addChild(ru_octasoft_oem_designer_Main.selected);
		this.showPopup(ru_octasoft_oem_designer_Main.selected,true);
	}
	,showPopup: function(figure,state) {
		if(state) {
			if(figure.type == ru_octasoft_oem_designer_ItemType.shelf) {
				ru_octasoft_oem_designer_Main.shelfPopup.set_visible(true);
				ru_octasoft_oem_designer_Main.shelfPopup.update(figure);
			} else ru_octasoft_oem_designer_Main.shelfPopup.set_visible(false);
		} else ru_octasoft_oem_designer_Main.shelfPopup.set_visible(false);
	}
	,nearWall: function(f) {
		var bounds = f.picture.getBounds(ru_octasoft_oem_designer_Main.grid);
		return ru_octasoft_oem_designer_Main.compare(f.get_x(),bounds.width / 2) || ru_octasoft_oem_designer_Main.compare(f.get_y(),bounds.height / 2) || ru_octasoft_oem_designer_Main.compare(f.get_x(),ru_octasoft_oem_designer_Main.grid.scaledW - bounds.width / 2) || ru_octasoft_oem_designer_Main.compare(f.get_y(),ru_octasoft_oem_designer_Main.grid.scaledH - bounds.height / 2);
	}
	,validateBounds: function(f) {
		var bounds = f.picture.getBounds(ru_octasoft_oem_designer_Main.grid);
		if(bounds.x < 0) f.set_x(bounds.width / 2);
		if(bounds.y < 0) f.set_y(bounds.height / 2);
		if(bounds.get_right() > ru_octasoft_oem_designer_Main.grid.scaledW) f.set_x(ru_octasoft_oem_designer_Main.grid.scaledW - bounds.width / 2);
		if(bounds.get_bottom() > ru_octasoft_oem_designer_Main.grid.scaledH) f.set_y(ru_octasoft_oem_designer_Main.grid.scaledH - bounds.height / 2);
		f.set_x(Math.round(f.get_x()));
		f.set_y(Math.round(f.get_y()));
	}
	,validatePosition: function(f) {
		var bounds = f.picture.getBounds(ru_octasoft_oem_designer_Main.grid);
		if(bounds.x < 0) f.set_x(bounds.width / 2);
		if(bounds.y < 0) f.set_y(bounds.height / 2);
		if(bounds.get_right() > ru_octasoft_oem_designer_Main.grid.scaledW) f.set_x(ru_octasoft_oem_designer_Main.grid.scaledW - bounds.width / 2);
		if(bounds.get_bottom() > ru_octasoft_oem_designer_Main.grid.scaledH) f.set_y(ru_octasoft_oem_designer_Main.grid.scaledH - bounds.height / 2);
		var _g = f.type;
		switch(_g[1]) {
		case 1:
			var d1 = f.get_x();
			var d2 = f.get_y();
			var d3 = ru_octasoft_oem_designer_Main.grid.scaledW - f.get_x();
			var d4 = ru_octasoft_oem_designer_Main.grid.scaledH - f.get_y();
			var min = Math.min(d1,Math.min(d2,Math.min(d3,d4)));
			if(min == d1) f.set_x(bounds.width / 2);
			if(min == d2) f.set_y(bounds.height / 2);
			if(min == d3) f.set_x(ru_octasoft_oem_designer_Main.grid.scaledW - bounds.width / 2);
			if(min == d4) f.set_y(ru_octasoft_oem_designer_Main.grid.scaledH - bounds.height / 2);
			break;
		case 3:
			var d11 = f.get_x();
			var d21 = f.get_y();
			var d31 = ru_octasoft_oem_designer_Main.grid.scaledW - f.get_x();
			var min1 = 0.;
			var _g1 = ru_octasoft_oem_designer_Main.grid.gridType;
			switch(_g1[1]) {
			case 0:
				min1 = Math.min(d11,Math.min(d21,d31));
				break;
			case 1:
				min1 = Math.min(d21,d31);
				break;
			case 2:
				min1 = d21;
				break;
			case 3:
				break;
			}
			if(min1 == d11) f.set_x(bounds.width / 2);
			if(min1 == d21) f.set_y(bounds.height / 2);
			if(min1 == d31) f.set_x(ru_octasoft_oem_designer_Main.grid.scaledW - bounds.width / 2);
			break;
		default:
		}
		f.set_x(Math.round(f.get_x()));
		f.set_y(Math.round(f.get_y()));
	}
	,validateDrop: function(f) {
		if((f.type == ru_octasoft_oem_designer_ItemType.light || f.type == ru_octasoft_oem_designer_ItemType.shelf) && ru_octasoft_oem_designer_Main.grid.gridType == ru_octasoft_oem_designer_GridType.island) return false;
		var _g1 = 0;
		var _g = ru_octasoft_oem_designer_Main.grid.get_numChildren();
		while(_g1 < _g) {
			var i = _g1++;
			var c = ru_octasoft_oem_designer_Main.grid.getChildAt(ru_octasoft_oem_designer_Main.grid.get_numChildren() - i - 1);
			if(js_Boot.__instanceof(c,ru_octasoft_oem_designer_figures_Figure) && f != c) {
				var tmp = c;
				var b1 = f.picture.getBounds(ru_octasoft_oem_designer_Main.grid);
				var b2 = tmp.picture.getBounds(ru_octasoft_oem_designer_Main.grid);
				if(b1.intersects(b2) && f.type != ru_octasoft_oem_designer_ItemType.plug && f.type != ru_octasoft_oem_designer_ItemType.shelf && (f.type != ru_octasoft_oem_designer_ItemType.light || !this.nearWall(tmp))) return false;
			}
		}
		return true;
	}
	,onUndo: function(e) {
		if(ru_octasoft_oem_designer_Main.undo.length == 0) return;
		var event = ru_octasoft_oem_designer_Main.undo.pop();
		ru_octasoft_oem_designer_Main.grid.dispatchEvent(event);
	}
	,onRedo: function(e) {
		if(ru_octasoft_oem_designer_Main.redo.length == 0) return;
		var event = ru_octasoft_oem_designer_Main.redo.pop();
		ru_octasoft_oem_designer_Main.grid.dispatchEvent(event);
	}
	,onSelect: function(e) {
		if(e.figure == ru_octasoft_oem_designer_Main.selected) {
			ru_octasoft_oem_designer_Main.selected = null;
			e.figure.set_selected(false);
			this.showPopup(ru_octasoft_oem_designer_Main.selected,false);
		} else {
			if(ru_octasoft_oem_designer_Main.selected != null) ru_octasoft_oem_designer_Main.selected.set_selected(false);
			ru_octasoft_oem_designer_Main.selected = e.figure;
			ru_octasoft_oem_designer_Main.selected.set_selected(true);
			this.showPopup(ru_octasoft_oem_designer_Main.selected,true);
		}
	}
	,listObjectsUnderMouse: function(e) {
		var point = new openfl_geom_Point(e.stageX,e.stageY);
		var objects = this.stage.getObjectsUnderPoint(point);
		if(objects.length > 0 && objects[0] != e.target) {
			var sp;
			sp = js_Boot.__cast(objects[0] , openfl_display_Sprite);
			sp.get_graphics().beginFill(16711680);
			sp.get_graphics().drawRect(0,0,100,100);
		}
	}
	,__class__: ru_octasoft_oem_designer_Main
});
var DocumentClass = function() {
	openfl_Lib.current.addChild(this);
	ru_octasoft_oem_designer_Main.call(this);
	this.dispatchEvent(new openfl_events_Event(openfl_events_Event.ADDED_TO_STAGE,false,false));
};
$hxClasses["DocumentClass"] = DocumentClass;
DocumentClass.__name__ = true;
DocumentClass.__super__ = ru_octasoft_oem_designer_Main;
DocumentClass.prototype = $extend(ru_octasoft_oem_designer_Main.prototype,{
	__class__: DocumentClass
});
var lime_AssetLibrary = function() {
	this.onChange = new lime_app_Event_$Void_$Void();
};
$hxClasses["lime.AssetLibrary"] = lime_AssetLibrary;
lime_AssetLibrary.__name__ = true;
lime_AssetLibrary.prototype = {
	exists: function(id,type) {
		return false;
	}
	,getFont: function(id) {
		return null;
	}
	,getImage: function(id) {
		return null;
	}
	,isLocal: function(id,type) {
		return true;
	}
	,unload: function() {
	}
	,__class__: lime_AssetLibrary
};
var DefaultAssetLibrary = function() {
	this.type = new haxe_ds_StringMap();
	this.path = new haxe_ds_StringMap();
	this.className = new haxe_ds_StringMap();
	lime_AssetLibrary.call(this);
	openfl_text_Font.registerFont(_$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaprobol_$webfont_$ttf);
	openfl_text_Font.registerFont(_$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaproreg_$webfont_$ttf);
	var id;
	id = "assets/fonts/gothaprobol-webfont.ttf";
	this.className.set(id,_$_$ASSET_$_$assets_$fonts_$gothaprobol_$webfont_$ttf);
	this.type.set(id,"FONT");
	id = "assets/fonts/gothaproreg-webfont.ttf";
	this.className.set(id,_$_$ASSET_$_$assets_$fonts_$gothaproreg_$webfont_$ttf);
	this.type.set(id,"FONT");
	id = "assets/images/border1.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/border2.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/decor.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/delete.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/popup_left.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/popup_right.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/popup_top.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/redo.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/rotate.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/shelf_bg1.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/undo.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/zoomIn.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	id = "assets/images/zoomOut.png";
	this.path.set(id,id);
	this.type.set(id,"IMAGE");
	var assetsPrefix = null;
	if(ApplicationMain.config != null && Object.prototype.hasOwnProperty.call(ApplicationMain.config,"assetsPrefix")) assetsPrefix = ApplicationMain.config.assetsPrefix;
	if(assetsPrefix != null) {
		var $it0 = this.path.keys();
		while( $it0.hasNext() ) {
			var k = $it0.next();
			var value = assetsPrefix + this.path.get(k);
			this.path.set(k,value);
		}
	}
};
$hxClasses["DefaultAssetLibrary"] = DefaultAssetLibrary;
DefaultAssetLibrary.__name__ = true;
DefaultAssetLibrary.__super__ = lime_AssetLibrary;
DefaultAssetLibrary.prototype = $extend(lime_AssetLibrary.prototype,{
	exists: function(id,type) {
		var requestedType;
		if(type != null) requestedType = js_Boot.__cast(type , String); else requestedType = null;
		var assetType = this.type.get(id);
		if(assetType != null) {
			if(assetType == requestedType || (requestedType == "SOUND" || requestedType == "MUSIC") && (assetType == "MUSIC" || assetType == "SOUND")) return true;
			if(requestedType == "BINARY" || requestedType == null || assetType == "BINARY" && requestedType == "TEXT") return true;
		}
		return false;
	}
	,getFont: function(id) {
		return js_Boot.__cast(Type.createInstance(this.className.get(id),[]) , lime_text_Font);
	}
	,getImage: function(id) {
		return lime_graphics_Image.fromImageElement((function($this) {
			var $r;
			var key = $this.path.get(id);
			$r = lime_app_Preloader.images.get(key);
			return $r;
		}(this)));
	}
	,isLocal: function(id,type) {
		var requestedType;
		if(type != null) requestedType = js_Boot.__cast(type , String); else requestedType = null;
		return true;
	}
	,__class__: DefaultAssetLibrary
});
var lime_text_Font = function(name) {
	if(name != null) this.name = name;
	if(this.__fontPath != null) this.__fromFile(this.__fontPath);
};
$hxClasses["lime.text.Font"] = lime_text_Font;
lime_text_Font.__name__ = true;
lime_text_Font.prototype = {
	__fromFile: function(path) {
		this.__fontPath = path;
	}
	,__class__: lime_text_Font
};
var _$_$ASSET_$_$assets_$fonts_$gothaprobol_$webfont_$ttf = function() {
	lime_text_Font.call(this);
	this.name = "Gotham Pro Bold";
};
$hxClasses["__ASSET__assets_fonts_gothaprobol_webfont_ttf"] = _$_$ASSET_$_$assets_$fonts_$gothaprobol_$webfont_$ttf;
_$_$ASSET_$_$assets_$fonts_$gothaprobol_$webfont_$ttf.__name__ = true;
_$_$ASSET_$_$assets_$fonts_$gothaprobol_$webfont_$ttf.__super__ = lime_text_Font;
_$_$ASSET_$_$assets_$fonts_$gothaprobol_$webfont_$ttf.prototype = $extend(lime_text_Font.prototype,{
	__class__: _$_$ASSET_$_$assets_$fonts_$gothaprobol_$webfont_$ttf
});
var _$_$ASSET_$_$assets_$fonts_$gothaproreg_$webfont_$ttf = function() {
	lime_text_Font.call(this);
	this.name = "Gotham Pro Regular";
};
$hxClasses["__ASSET__assets_fonts_gothaproreg_webfont_ttf"] = _$_$ASSET_$_$assets_$fonts_$gothaproreg_$webfont_$ttf;
_$_$ASSET_$_$assets_$fonts_$gothaproreg_$webfont_$ttf.__name__ = true;
_$_$ASSET_$_$assets_$fonts_$gothaproreg_$webfont_$ttf.__super__ = lime_text_Font;
_$_$ASSET_$_$assets_$fonts_$gothaproreg_$webfont_$ttf.prototype = $extend(lime_text_Font.prototype,{
	__class__: _$_$ASSET_$_$assets_$fonts_$gothaproreg_$webfont_$ttf
});
var openfl_text_Font = function(name) {
	lime_text_Font.call(this,name);
};
$hxClasses["openfl.text.Font"] = openfl_text_Font;
openfl_text_Font.__name__ = true;
openfl_text_Font.registerFont = function(font) {
	var instance;
	instance = js_Boot.__cast(Type.createInstance(font,[]) , openfl_text_Font);
	if(instance != null) openfl_text_Font.__registeredFonts.push(instance);
};
openfl_text_Font.__fromLimeFont = function(value) {
	var font = new openfl_text_Font();
	font.name = value.name;
	font.src = value.src;
	return font;
};
openfl_text_Font.__super__ = lime_text_Font;
openfl_text_Font.prototype = $extend(lime_text_Font.prototype,{
	__class__: openfl_text_Font
});
var _$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaprobol_$webfont_$ttf = function() {
	var font = new _$_$ASSET_$_$assets_$fonts_$gothaprobol_$webfont_$ttf();
	this.src = font.src;
	this.name = font.name;
	openfl_text_Font.call(this);
};
$hxClasses["__ASSET__OPENFL__assets_fonts_gothaprobol_webfont_ttf"] = _$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaprobol_$webfont_$ttf;
_$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaprobol_$webfont_$ttf.__name__ = true;
_$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaprobol_$webfont_$ttf.__super__ = openfl_text_Font;
_$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaprobol_$webfont_$ttf.prototype = $extend(openfl_text_Font.prototype,{
	__class__: _$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaprobol_$webfont_$ttf
});
var _$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaproreg_$webfont_$ttf = function() {
	var font = new _$_$ASSET_$_$assets_$fonts_$gothaproreg_$webfont_$ttf();
	this.src = font.src;
	this.name = font.name;
	openfl_text_Font.call(this);
};
$hxClasses["__ASSET__OPENFL__assets_fonts_gothaproreg_webfont_ttf"] = _$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaproreg_$webfont_$ttf;
_$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaproreg_$webfont_$ttf.__name__ = true;
_$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaproreg_$webfont_$ttf.__super__ = openfl_text_Font;
_$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaproreg_$webfont_$ttf.prototype = $extend(openfl_text_Font.prototype,{
	__class__: _$_$ASSET_$_$OPENFL_$_$assets_$fonts_$gothaproreg_$webfont_$ttf
});
var EReg = function(r,opt) {
	opt = opt.split("u").join("");
	this.r = new RegExp(r,opt);
};
$hxClasses["EReg"] = EReg;
EReg.__name__ = true;
EReg.prototype = {
	match: function(s) {
		if(this.r.global) this.r.lastIndex = 0;
		this.r.m = this.r.exec(s);
		this.r.s = s;
		return this.r.m != null;
	}
	,replace: function(s,by) {
		return s.replace(this.r,by);
	}
	,__class__: EReg
};
var HxOverrides = function() { };
$hxClasses["HxOverrides"] = HxOverrides;
HxOverrides.__name__ = true;
HxOverrides.cca = function(s,index) {
	var x = s.charCodeAt(index);
	if(x != x) return undefined;
	return x;
};
HxOverrides.substr = function(s,pos,len) {
	if(pos != null && pos != 0 && len != null && len < 0) return "";
	if(len == null) len = s.length;
	if(pos < 0) {
		pos = s.length + pos;
		if(pos < 0) pos = 0;
	} else if(len < 0) len = s.length + len - pos;
	return s.substr(pos,len);
};
HxOverrides.indexOf = function(a,obj,i) {
	var len = a.length;
	if(i < 0) {
		i += len;
		if(i < 0) i = 0;
	}
	while(i < len) {
		if(a[i] === obj) return i;
		i++;
	}
	return -1;
};
HxOverrides.remove = function(a,obj) {
	var i = HxOverrides.indexOf(a,obj,0);
	if(i == -1) return false;
	a.splice(i,1);
	return true;
};
HxOverrides.iter = function(a) {
	return { cur : 0, arr : a, hasNext : function() {
		return this.cur < this.arr.length;
	}, next : function() {
		return this.arr[this.cur++];
	}};
};
var Lambda = function() { };
$hxClasses["Lambda"] = Lambda;
Lambda.__name__ = true;
Lambda.array = function(it) {
	var a = [];
	var $it0 = $iterator(it)();
	while( $it0.hasNext() ) {
		var i = $it0.next();
		a.push(i);
	}
	return a;
};
Lambda.count = function(it,pred) {
	var n = 0;
	if(pred == null) {
		var $it0 = $iterator(it)();
		while( $it0.hasNext() ) {
			var _ = $it0.next();
			n++;
		}
	} else {
		var $it1 = $iterator(it)();
		while( $it1.hasNext() ) {
			var x = $it1.next();
			if(pred(x)) n++;
		}
	}
	return n;
};
var List = function() {
	this.length = 0;
};
$hxClasses["List"] = List;
List.__name__ = true;
List.prototype = {
	add: function(item) {
		var x = [item];
		if(this.h == null) this.h = x; else this.q[1] = x;
		this.q = x;
		this.length++;
	}
	,push: function(item) {
		var x = [item,this.h];
		this.h = x;
		if(this.q == null) this.q = x;
		this.length++;
	}
	,pop: function() {
		if(this.h == null) return null;
		var x = this.h[0];
		this.h = this.h[1];
		if(this.h == null) this.q = null;
		this.length--;
		return x;
	}
	,clear: function() {
		this.h = null;
		this.q = null;
		this.length = 0;
	}
	,__class__: List
};
Math.__name__ = true;
var NMEPreloader = function() {
	openfl_display_Sprite.call(this);
	var backgroundColor = this.getBackgroundColor();
	var r = backgroundColor >> 16 & 255;
	var g = backgroundColor >> 8 & 255;
	var b = backgroundColor & 255;
	var perceivedLuminosity = 0.299 * r + 0.587 * g + 0.114 * b;
	var color = 0;
	if(perceivedLuminosity < 70) color = 16777215;
	var x = 30;
	var height = 7;
	var y = this.getHeight() / 2 - height / 2;
	var width = this.getWidth() - x * 2;
	var padding = 2;
	this.outline = new openfl_display_Sprite();
	this.outline.get_graphics().beginFill(color,0.07);
	this.outline.get_graphics().drawRect(0,0,width,height);
	this.outline.set_x(x);
	this.outline.set_y(y);
	this.addChild(this.outline);
	this.progress = new openfl_display_Sprite();
	this.progress.get_graphics().beginFill(color,0.35);
	this.progress.get_graphics().drawRect(0,0,width - padding * 2,height - padding * 2);
	this.progress.set_x(x + padding);
	this.progress.set_y(y + padding);
	this.progress.set_scaleX(0);
	this.addChild(this.progress);
};
$hxClasses["NMEPreloader"] = NMEPreloader;
NMEPreloader.__name__ = true;
NMEPreloader.__super__ = openfl_display_Sprite;
NMEPreloader.prototype = $extend(openfl_display_Sprite.prototype,{
	getBackgroundColor: function() {
		return 16777215;
	}
	,getHeight: function() {
		var height = 0;
		if(height > 0) return height; else return openfl_Lib.current.stage.stageHeight;
	}
	,getWidth: function() {
		var width = 0;
		if(width > 0) return width; else return openfl_Lib.current.stage.stageWidth;
	}
	,onInit: function() {
	}
	,onLoaded: function() {
		this.dispatchEvent(new openfl_events_Event(openfl_events_Event.COMPLETE));
	}
	,onUpdate: function(bytesLoaded,bytesTotal) {
		var percentLoaded = bytesLoaded / bytesTotal;
		if(percentLoaded > 1) percentLoaded = 1;
		this.progress.set_scaleX(percentLoaded);
	}
	,__class__: NMEPreloader
});
var Reflect = function() { };
$hxClasses["Reflect"] = Reflect;
Reflect.__name__ = true;
Reflect.hasField = function(o,field) {
	return Object.prototype.hasOwnProperty.call(o,field);
};
Reflect.field = function(o,field) {
	try {
		return o[field];
	} catch( e ) {
		if (e instanceof js__$Boot_HaxeError) e = e.val;
		return null;
	}
};
Reflect.setField = function(o,field,value) {
	o[field] = value;
};
Reflect.getProperty = function(o,field) {
	var tmp;
	if(o == null) return null; else if(o.__properties__ && (tmp = o.__properties__["get_" + field])) return o[tmp](); else return o[field];
};
Reflect.setProperty = function(o,field,value) {
	var tmp;
	if(o.__properties__ && (tmp = o.__properties__["set_" + field])) o[tmp](value); else o[field] = value;
};
Reflect.callMethod = function(o,func,args) {
	return func.apply(o,args);
};
Reflect.fields = function(o) {
	var a = [];
	if(o != null) {
		var hasOwnProperty = Object.prototype.hasOwnProperty;
		for( var f in o ) {
		if(f != "__id__" && f != "hx__closures__" && hasOwnProperty.call(o,f)) a.push(f);
		}
	}
	return a;
};
Reflect.isFunction = function(f) {
	return typeof(f) == "function" && !(f.__name__ || f.__ename__);
};
Reflect.compareMethods = function(f1,f2) {
	if(f1 == f2) return true;
	if(!Reflect.isFunction(f1) || !Reflect.isFunction(f2)) return false;
	return f1.scope == f2.scope && f1.method == f2.method && f1.method != null;
};
var Std = function() { };
$hxClasses["Std"] = Std;
Std.__name__ = true;
Std.string = function(s) {
	return js_Boot.__string_rec(s,"");
};
Std["int"] = function(x) {
	return x | 0;
};
Std.parseInt = function(x) {
	var v = parseInt(x,10);
	if(v == 0 && (HxOverrides.cca(x,1) == 120 || HxOverrides.cca(x,1) == 88)) v = parseInt(x);
	if(isNaN(v)) return null;
	return v;
};
var StringTools = function() { };
$hxClasses["StringTools"] = StringTools;
StringTools.__name__ = true;
StringTools.urlEncode = function(s) {
	return encodeURIComponent(s);
};
StringTools.startsWith = function(s,start) {
	return s.length >= start.length && HxOverrides.substr(s,0,start.length) == start;
};
StringTools.isSpace = function(s,pos) {
	var c = HxOverrides.cca(s,pos);
	return c > 8 && c < 14 || c == 32;
};
StringTools.ltrim = function(s) {
	var l = s.length;
	var r = 0;
	while(r < l && StringTools.isSpace(s,r)) r++;
	if(r > 0) return HxOverrides.substr(s,r,l - r); else return s;
};
StringTools.rtrim = function(s) {
	var l = s.length;
	var r = 0;
	while(r < l && StringTools.isSpace(s,l - r - 1)) r++;
	if(r > 0) return HxOverrides.substr(s,0,l - r); else return s;
};
StringTools.trim = function(s) {
	return StringTools.ltrim(StringTools.rtrim(s));
};
StringTools.replace = function(s,sub,by) {
	return s.split(sub).join(by);
};
StringTools.hex = function(n,digits) {
	var s = "";
	var hexChars = "0123456789ABCDEF";
	do {
		s = hexChars.charAt(n & 15) + s;
		n >>>= 4;
	} while(n > 0);
	if(digits != null) while(s.length < digits) s = "0" + s;
	return s;
};
StringTools.fastCodeAt = function(s,index) {
	return s.charCodeAt(index);
};
var Type = function() { };
$hxClasses["Type"] = Type;
Type.__name__ = true;
Type.resolveClass = function(name) {
	var cl = $hxClasses[name];
	if(cl == null || !cl.__name__) return null;
	return cl;
};
Type.resolveEnum = function(name) {
	var e = $hxClasses[name];
	if(e == null || !e.__ename__) return null;
	return e;
};
Type.createInstance = function(cl,args) {
	var _g = args.length;
	switch(_g) {
	case 0:
		return new cl();
	case 1:
		return new cl(args[0]);
	case 2:
		return new cl(args[0],args[1]);
	case 3:
		return new cl(args[0],args[1],args[2]);
	case 4:
		return new cl(args[0],args[1],args[2],args[3]);
	case 5:
		return new cl(args[0],args[1],args[2],args[3],args[4]);
	case 6:
		return new cl(args[0],args[1],args[2],args[3],args[4],args[5]);
	case 7:
		return new cl(args[0],args[1],args[2],args[3],args[4],args[5],args[6]);
	case 8:
		return new cl(args[0],args[1],args[2],args[3],args[4],args[5],args[6],args[7]);
	default:
		throw new js__$Boot_HaxeError("Too many arguments");
	}
	return null;
};
Type.createEnum = function(e,constr,params) {
	var f = Reflect.field(e,constr);
	if(f == null) throw new js__$Boot_HaxeError("No such constructor " + constr);
	if(Reflect.isFunction(f)) {
		if(params == null) throw new js__$Boot_HaxeError("Constructor " + constr + " need parameters");
		return Reflect.callMethod(e,f,params);
	}
	if(params != null && params.length != 0) throw new js__$Boot_HaxeError("Constructor " + constr + " does not need parameters");
	return f;
};
Type.getClassFields = function(c) {
	var a = Reflect.fields(c);
	HxOverrides.remove(a,"__name__");
	HxOverrides.remove(a,"__interfaces__");
	HxOverrides.remove(a,"__properties__");
	HxOverrides.remove(a,"__super__");
	HxOverrides.remove(a,"__meta__");
	HxOverrides.remove(a,"prototype");
	return a;
};
var _$UInt_UInt_$Impl_$ = {};
$hxClasses["_UInt.UInt_Impl_"] = _$UInt_UInt_$Impl_$;
_$UInt_UInt_$Impl_$.__name__ = true;
_$UInt_UInt_$Impl_$.toFloat = function(this1) {
	var $int = this1;
	if($int < 0) return 4294967296.0 + $int; else return $int + 0.0;
};
var haxe_IMap = function() { };
$hxClasses["haxe.IMap"] = haxe_IMap;
haxe_IMap.__name__ = true;
var haxe__$Int64__$_$_$Int64 = function(high,low) {
	this.high = high;
	this.low = low;
};
$hxClasses["haxe._Int64.___Int64"] = haxe__$Int64__$_$_$Int64;
haxe__$Int64__$_$_$Int64.__name__ = true;
haxe__$Int64__$_$_$Int64.prototype = {
	__class__: haxe__$Int64__$_$_$Int64
};
var haxe_Timer = function(time_ms) {
	var me = this;
	this.id = setInterval(function() {
		me.run();
	},time_ms);
};
$hxClasses["haxe.Timer"] = haxe_Timer;
haxe_Timer.__name__ = true;
haxe_Timer.delay = function(f,time_ms) {
	var t = new haxe_Timer(time_ms);
	t.run = function() {
		t.stop();
		f();
	};
	return t;
};
haxe_Timer.prototype = {
	stop: function() {
		if(this.id == null) return;
		clearInterval(this.id);
		this.id = null;
	}
	,run: function() {
	}
	,__class__: haxe_Timer
};
var haxe_io_Bytes = function(data) {
	this.length = data.byteLength;
	this.b = new Uint8Array(data);
	this.b.bufferValue = data;
	data.hxBytes = this;
	data.bytes = this.b;
};
$hxClasses["haxe.io.Bytes"] = haxe_io_Bytes;
haxe_io_Bytes.__name__ = true;
haxe_io_Bytes.alloc = function(length) {
	return new haxe_io_Bytes(new ArrayBuffer(length));
};
haxe_io_Bytes.ofString = function(s) {
	var a = [];
	var i = 0;
	while(i < s.length) {
		var c = StringTools.fastCodeAt(s,i++);
		if(55296 <= c && c <= 56319) c = c - 55232 << 10 | StringTools.fastCodeAt(s,i++) & 1023;
		if(c <= 127) a.push(c); else if(c <= 2047) {
			a.push(192 | c >> 6);
			a.push(128 | c & 63);
		} else if(c <= 65535) {
			a.push(224 | c >> 12);
			a.push(128 | c >> 6 & 63);
			a.push(128 | c & 63);
		} else {
			a.push(240 | c >> 18);
			a.push(128 | c >> 12 & 63);
			a.push(128 | c >> 6 & 63);
			a.push(128 | c & 63);
		}
	}
	return new haxe_io_Bytes(new Uint8Array(a).buffer);
};
haxe_io_Bytes.ofData = function(b) {
	var hb = b.hxBytes;
	if(hb != null) return hb;
	return new haxe_io_Bytes(b);
};
haxe_io_Bytes.prototype = {
	get: function(pos) {
		return this.b[pos];
	}
	,set: function(pos,v) {
		this.b[pos] = v & 255;
	}
	,getString: function(pos,len) {
		if(pos < 0 || len < 0 || pos + len > this.length) throw new js__$Boot_HaxeError(haxe_io_Error.OutsideBounds);
		var s = "";
		var b = this.b;
		var fcc = String.fromCharCode;
		var i = pos;
		var max = pos + len;
		while(i < max) {
			var c = b[i++];
			if(c < 128) {
				if(c == 0) break;
				s += fcc(c);
			} else if(c < 224) s += fcc((c & 63) << 6 | b[i++] & 127); else if(c < 240) {
				var c2 = b[i++];
				s += fcc((c & 31) << 12 | (c2 & 127) << 6 | b[i++] & 127);
			} else {
				var c21 = b[i++];
				var c3 = b[i++];
				var u = (c & 15) << 18 | (c21 & 127) << 12 | (c3 & 127) << 6 | b[i++] & 127;
				s += fcc((u >> 10) + 55232);
				s += fcc(u & 1023 | 56320);
			}
		}
		return s;
	}
	,toString: function() {
		return this.getString(0,this.length);
	}
	,__class__: haxe_io_Bytes
};
var haxe_crypto_Base64 = function() { };
$hxClasses["haxe.crypto.Base64"] = haxe_crypto_Base64;
haxe_crypto_Base64.__name__ = true;
haxe_crypto_Base64.encode = function(bytes,complement) {
	if(complement == null) complement = true;
	var str = new haxe_crypto_BaseCode(haxe_crypto_Base64.BYTES).encodeBytes(bytes).toString();
	if(complement) {
		var _g = bytes.length % 3;
		switch(_g) {
		case 1:
			str += "==";
			break;
		case 2:
			str += "=";
			break;
		default:
		}
	}
	return str;
};
var haxe_crypto_BaseCode = function(base) {
	var len = base.length;
	var nbits = 1;
	while(len > 1 << nbits) nbits++;
	if(nbits > 8 || len != 1 << nbits) throw new js__$Boot_HaxeError("BaseCode : base length must be a power of two.");
	this.base = base;
	this.nbits = nbits;
};
$hxClasses["haxe.crypto.BaseCode"] = haxe_crypto_BaseCode;
haxe_crypto_BaseCode.__name__ = true;
haxe_crypto_BaseCode.prototype = {
	encodeBytes: function(b) {
		var nbits = this.nbits;
		var base = this.base;
		var size = b.length * 8 / nbits | 0;
		var out = haxe_io_Bytes.alloc(size + (b.length * 8 % nbits == 0?0:1));
		var buf = 0;
		var curbits = 0;
		var mask = (1 << nbits) - 1;
		var pin = 0;
		var pout = 0;
		while(pout < size) {
			while(curbits < nbits) {
				curbits += 8;
				buf <<= 8;
				buf |= b.get(pin++);
			}
			curbits -= nbits;
			out.set(pout++,base.b[buf >> curbits & mask]);
		}
		if(curbits > 0) out.set(pout++,base.b[buf << nbits - curbits & mask]);
		return out;
	}
	,__class__: haxe_crypto_BaseCode
};
var haxe_crypto_Md5 = function() {
};
$hxClasses["haxe.crypto.Md5"] = haxe_crypto_Md5;
haxe_crypto_Md5.__name__ = true;
haxe_crypto_Md5.encode = function(s) {
	var m = new haxe_crypto_Md5();
	var h = m.doEncode(haxe_crypto_Md5.str2blks(s));
	return m.hex(h);
};
haxe_crypto_Md5.str2blks = function(str) {
	var nblk = (str.length + 8 >> 6) + 1;
	var blks = [];
	var blksSize = nblk * 16;
	var _g = 0;
	while(_g < blksSize) {
		var i1 = _g++;
		blks[i1] = 0;
	}
	var i = 0;
	while(i < str.length) {
		blks[i >> 2] |= HxOverrides.cca(str,i) << (str.length * 8 + i) % 4 * 8;
		i++;
	}
	blks[i >> 2] |= 128 << (str.length * 8 + i) % 4 * 8;
	var l = str.length * 8;
	var k = nblk * 16 - 2;
	blks[k] = l & 255;
	blks[k] |= (l >>> 8 & 255) << 8;
	blks[k] |= (l >>> 16 & 255) << 16;
	blks[k] |= (l >>> 24 & 255) << 24;
	return blks;
};
haxe_crypto_Md5.prototype = {
	bitOR: function(a,b) {
		var lsb = a & 1 | b & 1;
		var msb31 = a >>> 1 | b >>> 1;
		return msb31 << 1 | lsb;
	}
	,bitXOR: function(a,b) {
		var lsb = a & 1 ^ b & 1;
		var msb31 = a >>> 1 ^ b >>> 1;
		return msb31 << 1 | lsb;
	}
	,bitAND: function(a,b) {
		var lsb = a & 1 & (b & 1);
		var msb31 = a >>> 1 & b >>> 1;
		return msb31 << 1 | lsb;
	}
	,addme: function(x,y) {
		var lsw = (x & 65535) + (y & 65535);
		var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
		return msw << 16 | lsw & 65535;
	}
	,hex: function(a) {
		var str = "";
		var hex_chr = "0123456789abcdef";
		var _g = 0;
		while(_g < a.length) {
			var num = a[_g];
			++_g;
			var _g1 = 0;
			while(_g1 < 4) {
				var j = _g1++;
				str += hex_chr.charAt(num >> j * 8 + 4 & 15) + hex_chr.charAt(num >> j * 8 & 15);
			}
		}
		return str;
	}
	,rol: function(num,cnt) {
		return num << cnt | num >>> 32 - cnt;
	}
	,cmn: function(q,a,b,x,s,t) {
		return this.addme(this.rol(this.addme(this.addme(a,q),this.addme(x,t)),s),b);
	}
	,ff: function(a,b,c,d,x,s,t) {
		return this.cmn(this.bitOR(this.bitAND(b,c),this.bitAND(~b,d)),a,b,x,s,t);
	}
	,gg: function(a,b,c,d,x,s,t) {
		return this.cmn(this.bitOR(this.bitAND(b,d),this.bitAND(c,~d)),a,b,x,s,t);
	}
	,hh: function(a,b,c,d,x,s,t) {
		return this.cmn(this.bitXOR(this.bitXOR(b,c),d),a,b,x,s,t);
	}
	,ii: function(a,b,c,d,x,s,t) {
		return this.cmn(this.bitXOR(c,this.bitOR(b,~d)),a,b,x,s,t);
	}
	,doEncode: function(x) {
		var a = 1732584193;
		var b = -271733879;
		var c = -1732584194;
		var d = 271733878;
		var step;
		var i = 0;
		while(i < x.length) {
			var olda = a;
			var oldb = b;
			var oldc = c;
			var oldd = d;
			step = 0;
			a = this.ff(a,b,c,d,x[i],7,-680876936);
			d = this.ff(d,a,b,c,x[i + 1],12,-389564586);
			c = this.ff(c,d,a,b,x[i + 2],17,606105819);
			b = this.ff(b,c,d,a,x[i + 3],22,-1044525330);
			a = this.ff(a,b,c,d,x[i + 4],7,-176418897);
			d = this.ff(d,a,b,c,x[i + 5],12,1200080426);
			c = this.ff(c,d,a,b,x[i + 6],17,-1473231341);
			b = this.ff(b,c,d,a,x[i + 7],22,-45705983);
			a = this.ff(a,b,c,d,x[i + 8],7,1770035416);
			d = this.ff(d,a,b,c,x[i + 9],12,-1958414417);
			c = this.ff(c,d,a,b,x[i + 10],17,-42063);
			b = this.ff(b,c,d,a,x[i + 11],22,-1990404162);
			a = this.ff(a,b,c,d,x[i + 12],7,1804603682);
			d = this.ff(d,a,b,c,x[i + 13],12,-40341101);
			c = this.ff(c,d,a,b,x[i + 14],17,-1502002290);
			b = this.ff(b,c,d,a,x[i + 15],22,1236535329);
			a = this.gg(a,b,c,d,x[i + 1],5,-165796510);
			d = this.gg(d,a,b,c,x[i + 6],9,-1069501632);
			c = this.gg(c,d,a,b,x[i + 11],14,643717713);
			b = this.gg(b,c,d,a,x[i],20,-373897302);
			a = this.gg(a,b,c,d,x[i + 5],5,-701558691);
			d = this.gg(d,a,b,c,x[i + 10],9,38016083);
			c = this.gg(c,d,a,b,x[i + 15],14,-660478335);
			b = this.gg(b,c,d,a,x[i + 4],20,-405537848);
			a = this.gg(a,b,c,d,x[i + 9],5,568446438);
			d = this.gg(d,a,b,c,x[i + 14],9,-1019803690);
			c = this.gg(c,d,a,b,x[i + 3],14,-187363961);
			b = this.gg(b,c,d,a,x[i + 8],20,1163531501);
			a = this.gg(a,b,c,d,x[i + 13],5,-1444681467);
			d = this.gg(d,a,b,c,x[i + 2],9,-51403784);
			c = this.gg(c,d,a,b,x[i + 7],14,1735328473);
			b = this.gg(b,c,d,a,x[i + 12],20,-1926607734);
			a = this.hh(a,b,c,d,x[i + 5],4,-378558);
			d = this.hh(d,a,b,c,x[i + 8],11,-2022574463);
			c = this.hh(c,d,a,b,x[i + 11],16,1839030562);
			b = this.hh(b,c,d,a,x[i + 14],23,-35309556);
			a = this.hh(a,b,c,d,x[i + 1],4,-1530992060);
			d = this.hh(d,a,b,c,x[i + 4],11,1272893353);
			c = this.hh(c,d,a,b,x[i + 7],16,-155497632);
			b = this.hh(b,c,d,a,x[i + 10],23,-1094730640);
			a = this.hh(a,b,c,d,x[i + 13],4,681279174);
			d = this.hh(d,a,b,c,x[i],11,-358537222);
			c = this.hh(c,d,a,b,x[i + 3],16,-722521979);
			b = this.hh(b,c,d,a,x[i + 6],23,76029189);
			a = this.hh(a,b,c,d,x[i + 9],4,-640364487);
			d = this.hh(d,a,b,c,x[i + 12],11,-421815835);
			c = this.hh(c,d,a,b,x[i + 15],16,530742520);
			b = this.hh(b,c,d,a,x[i + 2],23,-995338651);
			a = this.ii(a,b,c,d,x[i],6,-198630844);
			d = this.ii(d,a,b,c,x[i + 7],10,1126891415);
			c = this.ii(c,d,a,b,x[i + 14],15,-1416354905);
			b = this.ii(b,c,d,a,x[i + 5],21,-57434055);
			a = this.ii(a,b,c,d,x[i + 12],6,1700485571);
			d = this.ii(d,a,b,c,x[i + 3],10,-1894986606);
			c = this.ii(c,d,a,b,x[i + 10],15,-1051523);
			b = this.ii(b,c,d,a,x[i + 1],21,-2054922799);
			a = this.ii(a,b,c,d,x[i + 8],6,1873313359);
			d = this.ii(d,a,b,c,x[i + 15],10,-30611744);
			c = this.ii(c,d,a,b,x[i + 6],15,-1560198380);
			b = this.ii(b,c,d,a,x[i + 13],21,1309151649);
			a = this.ii(a,b,c,d,x[i + 4],6,-145523070);
			d = this.ii(d,a,b,c,x[i + 11],10,-1120210379);
			c = this.ii(c,d,a,b,x[i + 2],15,718787259);
			b = this.ii(b,c,d,a,x[i + 9],21,-343485551);
			a = this.addme(a,olda);
			b = this.addme(b,oldb);
			c = this.addme(c,oldc);
			d = this.addme(d,oldd);
			i += 16;
		}
		return [a,b,c,d];
	}
	,__class__: haxe_crypto_Md5
};
var haxe_ds_IntMap = function() {
	this.h = { };
};
$hxClasses["haxe.ds.IntMap"] = haxe_ds_IntMap;
haxe_ds_IntMap.__name__ = true;
haxe_ds_IntMap.__interfaces__ = [haxe_IMap];
haxe_ds_IntMap.prototype = {
	remove: function(key) {
		if(!this.h.hasOwnProperty(key)) return false;
		delete(this.h[key]);
		return true;
	}
	,__class__: haxe_ds_IntMap
};
var haxe_ds_ObjectMap = function() {
	this.h = { };
	this.h.__keys__ = { };
};
$hxClasses["haxe.ds.ObjectMap"] = haxe_ds_ObjectMap;
haxe_ds_ObjectMap.__name__ = true;
haxe_ds_ObjectMap.__interfaces__ = [haxe_IMap];
haxe_ds_ObjectMap.prototype = {
	set: function(key,value) {
		var id = key.__id__ || (key.__id__ = ++haxe_ds_ObjectMap.count);
		this.h[id] = value;
		this.h.__keys__[id] = key;
	}
	,remove: function(key) {
		var id = key.__id__;
		if(this.h.__keys__[id] == null) return false;
		delete(this.h[id]);
		delete(this.h.__keys__[id]);
		return true;
	}
	,keys: function() {
		var a = [];
		for( var key in this.h.__keys__ ) {
		if(this.h.hasOwnProperty(key)) a.push(this.h.__keys__[key]);
		}
		return HxOverrides.iter(a);
	}
	,iterator: function() {
		return { ref : this.h, it : this.keys(), hasNext : function() {
			return this.it.hasNext();
		}, next : function() {
			var i = this.it.next();
			return this.ref[i.__id__];
		}};
	}
	,__class__: haxe_ds_ObjectMap
};
var haxe_ds__$StringMap_StringMapIterator = function(map,keys) {
	this.map = map;
	this.keys = keys;
	this.index = 0;
	this.count = keys.length;
};
$hxClasses["haxe.ds._StringMap.StringMapIterator"] = haxe_ds__$StringMap_StringMapIterator;
haxe_ds__$StringMap_StringMapIterator.__name__ = true;
haxe_ds__$StringMap_StringMapIterator.prototype = {
	hasNext: function() {
		return this.index < this.count;
	}
	,next: function() {
		return this.map.get(this.keys[this.index++]);
	}
	,__class__: haxe_ds__$StringMap_StringMapIterator
};
var haxe_ds_StringMap = function() {
	this.h = { };
};
$hxClasses["haxe.ds.StringMap"] = haxe_ds_StringMap;
haxe_ds_StringMap.__name__ = true;
haxe_ds_StringMap.__interfaces__ = [haxe_IMap];
haxe_ds_StringMap.prototype = {
	set: function(key,value) {
		if(__map_reserved[key] != null) this.setReserved(key,value); else this.h[key] = value;
	}
	,get: function(key) {
		if(__map_reserved[key] != null) return this.getReserved(key);
		return this.h[key];
	}
	,exists: function(key) {
		if(__map_reserved[key] != null) return this.existsReserved(key);
		return this.h.hasOwnProperty(key);
	}
	,setReserved: function(key,value) {
		if(this.rh == null) this.rh = { };
		this.rh["$" + key] = value;
	}
	,getReserved: function(key) {
		if(this.rh == null) return null; else return this.rh["$" + key];
	}
	,existsReserved: function(key) {
		if(this.rh == null) return false;
		return this.rh.hasOwnProperty("$" + key);
	}
	,remove: function(key) {
		if(__map_reserved[key] != null) {
			key = "$" + key;
			if(this.rh == null || !this.rh.hasOwnProperty(key)) return false;
			delete(this.rh[key]);
			return true;
		} else {
			if(!this.h.hasOwnProperty(key)) return false;
			delete(this.h[key]);
			return true;
		}
	}
	,keys: function() {
		var _this = this.arrayKeys();
		return HxOverrides.iter(_this);
	}
	,arrayKeys: function() {
		var out = [];
		for( var key in this.h ) {
		if(this.h.hasOwnProperty(key)) out.push(key);
		}
		if(this.rh != null) {
			for( var key in this.rh ) {
			if(key.charCodeAt(0) == 36) out.push(key.substr(1));
			}
		}
		return out;
	}
	,iterator: function() {
		return new haxe_ds__$StringMap_StringMapIterator(this,this.arrayKeys());
	}
	,__class__: haxe_ds_StringMap
};
var haxe_ds__$Vector_Vector_$Impl_$ = {};
$hxClasses["haxe.ds._Vector.Vector_Impl_"] = haxe_ds__$Vector_Vector_$Impl_$;
haxe_ds__$Vector_Vector_$Impl_$.__name__ = true;
haxe_ds__$Vector_Vector_$Impl_$.blit = function(src,srcPos,dest,destPos,len) {
	var _g = 0;
	while(_g < len) {
		var i = _g++;
		dest[destPos + i] = src[srcPos + i];
	}
};
var haxe_io_BytesBuffer = function() {
	this.b = [];
};
$hxClasses["haxe.io.BytesBuffer"] = haxe_io_BytesBuffer;
haxe_io_BytesBuffer.__name__ = true;
haxe_io_BytesBuffer.prototype = {
	addBytes: function(src,pos,len) {
		if(pos < 0 || len < 0 || pos + len > src.length) throw new js__$Boot_HaxeError(haxe_io_Error.OutsideBounds);
		var b1 = this.b;
		var b2 = src.b;
		var _g1 = pos;
		var _g = pos + len;
		while(_g1 < _g) {
			var i = _g1++;
			this.b.push(b2[i]);
		}
	}
	,getBytes: function() {
		var bytes = new haxe_io_Bytes(new Uint8Array(this.b).buffer);
		this.b = null;
		return bytes;
	}
	,__class__: haxe_io_BytesBuffer
};
var haxe_io_Output = function() { };
$hxClasses["haxe.io.Output"] = haxe_io_Output;
haxe_io_Output.__name__ = true;
haxe_io_Output.prototype = {
	writeByte: function(c) {
		throw new js__$Boot_HaxeError("Not implemented");
	}
	,writeBytes: function(s,pos,len) {
		var k = len;
		var b = s.b.bufferValue;
		if(pos < 0 || len < 0 || pos + len > s.length) throw new js__$Boot_HaxeError(haxe_io_Error.OutsideBounds);
		while(k > 0) {
			this.writeByte(b[pos]);
			pos++;
			k--;
		}
		return len;
	}
	,write: function(s) {
		var l = s.length;
		var p = 0;
		while(l > 0) {
			var k = this.writeBytes(s,p,l);
			if(k == 0) throw new js__$Boot_HaxeError(haxe_io_Error.Blocked);
			p += k;
			l -= k;
		}
	}
	,__class__: haxe_io_Output
};
var haxe_io_BytesOutput = function() {
	this.b = new haxe_io_BytesBuffer();
};
$hxClasses["haxe.io.BytesOutput"] = haxe_io_BytesOutput;
haxe_io_BytesOutput.__name__ = true;
haxe_io_BytesOutput.__super__ = haxe_io_Output;
haxe_io_BytesOutput.prototype = $extend(haxe_io_Output.prototype,{
	writeByte: function(c) {
		this.b.b.push(c);
	}
	,writeBytes: function(buf,pos,len) {
		this.b.addBytes(buf,pos,len);
		return len;
	}
	,getBytes: function() {
		return this.b.getBytes();
	}
	,__class__: haxe_io_BytesOutput
});
var haxe_io_Eof = function() { };
$hxClasses["haxe.io.Eof"] = haxe_io_Eof;
haxe_io_Eof.__name__ = true;
haxe_io_Eof.prototype = {
	toString: function() {
		return "Eof";
	}
	,__class__: haxe_io_Eof
};
var haxe_io_Error = $hxClasses["haxe.io.Error"] = { __ename__ : true, __constructs__ : ["Blocked","Overflow","OutsideBounds","Custom"] };
haxe_io_Error.Blocked = ["Blocked",0];
haxe_io_Error.Blocked.toString = $estr;
haxe_io_Error.Blocked.__enum__ = haxe_io_Error;
haxe_io_Error.Overflow = ["Overflow",1];
haxe_io_Error.Overflow.toString = $estr;
haxe_io_Error.Overflow.__enum__ = haxe_io_Error;
haxe_io_Error.OutsideBounds = ["OutsideBounds",2];
haxe_io_Error.OutsideBounds.toString = $estr;
haxe_io_Error.OutsideBounds.__enum__ = haxe_io_Error;
haxe_io_Error.Custom = function(e) { var $x = ["Custom",3,e]; $x.__enum__ = haxe_io_Error; $x.toString = $estr; return $x; };
var haxe_io_FPHelper = function() { };
$hxClasses["haxe.io.FPHelper"] = haxe_io_FPHelper;
haxe_io_FPHelper.__name__ = true;
haxe_io_FPHelper.i32ToFloat = function(i) {
	var sign = 1 - (i >>> 31 << 1);
	var exp = i >>> 23 & 255;
	var sig = i & 8388607;
	if(sig == 0 && exp == 0) return 0.0;
	return sign * (1 + Math.pow(2,-23) * sig) * Math.pow(2,exp - 127);
};
haxe_io_FPHelper.floatToI32 = function(f) {
	if(f == 0) return 0;
	var af;
	if(f < 0) af = -f; else af = f;
	var exp = Math.floor(Math.log(af) / 0.6931471805599453);
	if(exp < -127) exp = -127; else if(exp > 128) exp = 128;
	var sig = Math.round((af / Math.pow(2,exp) - 1) * 8388608) & 8388607;
	return (f < 0?-2147483648:0) | exp + 127 << 23 | sig;
};
haxe_io_FPHelper.i64ToDouble = function(low,high) {
	var sign = 1 - (high >>> 31 << 1);
	var exp = (high >> 20 & 2047) - 1023;
	var sig = (high & 1048575) * 4294967296. + (low >>> 31) * 2147483648. + (low & 2147483647);
	if(sig == 0 && exp == -1023) return 0.0;
	return sign * (1.0 + Math.pow(2,-52) * sig) * Math.pow(2,exp);
};
haxe_io_FPHelper.doubleToI64 = function(v) {
	var i64 = haxe_io_FPHelper.i64tmp;
	if(v == 0) {
		i64.low = 0;
		i64.high = 0;
	} else {
		var av;
		if(v < 0) av = -v; else av = v;
		var exp = Math.floor(Math.log(av) / 0.6931471805599453);
		var sig;
		var v1 = (av / Math.pow(2,exp) - 1) * 4503599627370496.;
		sig = Math.round(v1);
		var sig_l = sig | 0;
		var sig_h = sig / 4294967296.0 | 0;
		i64.low = sig_l;
		i64.high = (v < 0?-2147483648:0) | exp + 1023 << 20 | sig_h;
	}
	return i64;
};
var haxe_io_Path = function(path) {
	switch(path) {
	case ".":case "..":
		this.dir = path;
		this.file = "";
		return;
	}
	var c1 = path.lastIndexOf("/");
	var c2 = path.lastIndexOf("\\");
	if(c1 < c2) {
		this.dir = HxOverrides.substr(path,0,c2);
		path = HxOverrides.substr(path,c2 + 1,null);
		this.backslash = true;
	} else if(c2 < c1) {
		this.dir = HxOverrides.substr(path,0,c1);
		path = HxOverrides.substr(path,c1 + 1,null);
	} else this.dir = null;
	var cp = path.lastIndexOf(".");
	if(cp != -1) {
		this.ext = HxOverrides.substr(path,cp + 1,null);
		this.file = HxOverrides.substr(path,0,cp);
	} else {
		this.ext = null;
		this.file = path;
	}
};
$hxClasses["haxe.io.Path"] = haxe_io_Path;
haxe_io_Path.__name__ = true;
haxe_io_Path.withoutExtension = function(path) {
	var s = new haxe_io_Path(path);
	s.ext = null;
	return s.toString();
};
haxe_io_Path.prototype = {
	toString: function() {
		return (this.dir == null?"":this.dir + (this.backslash?"\\":"/")) + this.file + (this.ext == null?"":"." + this.ext);
	}
	,__class__: haxe_io_Path
};
var js__$Boot_HaxeError = function(val) {
	Error.call(this);
	this.val = val;
	if(Object.prototype.hasOwnProperty.call(val,"name")) this.name = Reflect.field(val,"name"); else this.name = "Error";
	if(Object.prototype.hasOwnProperty.call(val,"message")) this.message = Reflect.field(val,"message"); else this.message = Std.string(val);
	if(Error.captureStackTrace) Error.captureStackTrace(this,js__$Boot_HaxeError);
};
$hxClasses["js._Boot.HaxeError"] = js__$Boot_HaxeError;
js__$Boot_HaxeError.__name__ = true;
js__$Boot_HaxeError.__super__ = Error;
js__$Boot_HaxeError.prototype = $extend(Error.prototype,{
	__class__: js__$Boot_HaxeError
});
var js_Boot = function() { };
$hxClasses["js.Boot"] = js_Boot;
js_Boot.__name__ = true;
js_Boot.getClass = function(o) {
	if((o instanceof Array) && o.__enum__ == null) return Array; else {
		var cl = o.__class__;
		if(cl != null) return cl;
		var name = js_Boot.__nativeClassName(o);
		if(name != null) return js_Boot.__resolveNativeClass(name);
		return null;
	}
};
js_Boot.__string_rec = function(o,s) {
	if(o == null) return "null";
	if(s.length >= 5) return "<...>";
	var t = typeof(o);
	if(t == "function" && (o.__name__ || o.__ename__)) t = "object";
	switch(t) {
	case "object":
		if(o instanceof Array) {
			if(o.__enum__) {
				if(o.length == 2) return o[0];
				var str2 = o[0] + "(";
				s += "\t";
				var _g1 = 2;
				var _g = o.length;
				while(_g1 < _g) {
					var i1 = _g1++;
					if(i1 != 2) str2 += "," + js_Boot.__string_rec(o[i1],s); else str2 += js_Boot.__string_rec(o[i1],s);
				}
				return str2 + ")";
			}
			var l = o.length;
			var i;
			var str1 = "[";
			s += "\t";
			var _g2 = 0;
			while(_g2 < l) {
				var i2 = _g2++;
				str1 += (i2 > 0?",":"") + js_Boot.__string_rec(o[i2],s);
			}
			str1 += "]";
			return str1;
		}
		var tostr;
		try {
			tostr = o.toString;
		} catch( e ) {
			if (e instanceof js__$Boot_HaxeError) e = e.val;
			return "???";
		}
		if(tostr != null && tostr != Object.toString && typeof(tostr) == "function") {
			var s2 = o.toString();
			if(s2 != "[object Object]") return s2;
		}
		var k = null;
		var str = "{\n";
		s += "\t";
		var hasp = o.hasOwnProperty != null;
		for( var k in o ) {
		if(hasp && !o.hasOwnProperty(k)) {
			continue;
		}
		if(k == "prototype" || k == "__class__" || k == "__super__" || k == "__interfaces__" || k == "__properties__") {
			continue;
		}
		if(str.length != 2) str += ", \n";
		str += s + k + " : " + js_Boot.__string_rec(o[k],s);
		}
		s = s.substring(1);
		str += "\n" + s + "}";
		return str;
	case "function":
		return "<function>";
	case "string":
		return o;
	default:
		return String(o);
	}
};
js_Boot.__interfLoop = function(cc,cl) {
	if(cc == null) return false;
	if(cc == cl) return true;
	var intf = cc.__interfaces__;
	if(intf != null) {
		var _g1 = 0;
		var _g = intf.length;
		while(_g1 < _g) {
			var i = _g1++;
			var i1 = intf[i];
			if(i1 == cl || js_Boot.__interfLoop(i1,cl)) return true;
		}
	}
	return js_Boot.__interfLoop(cc.__super__,cl);
};
js_Boot.__instanceof = function(o,cl) {
	if(cl == null) return false;
	switch(cl) {
	case Int:
		return (o|0) === o;
	case Float:
		return typeof(o) == "number";
	case Bool:
		return typeof(o) == "boolean";
	case String:
		return typeof(o) == "string";
	case Array:
		return (o instanceof Array) && o.__enum__ == null;
	case Dynamic:
		return true;
	default:
		if(o != null) {
			if(typeof(cl) == "function") {
				if(o instanceof cl) return true;
				if(js_Boot.__interfLoop(js_Boot.getClass(o),cl)) return true;
			} else if(typeof(cl) == "object" && js_Boot.__isNativeObj(cl)) {
				if(o instanceof cl) return true;
			}
		} else return false;
		if(cl == Class && o.__name__ != null) return true;
		if(cl == Enum && o.__ename__ != null) return true;
		return o.__enum__ == cl;
	}
};
js_Boot.__cast = function(o,t) {
	if(js_Boot.__instanceof(o,t)) return o; else throw new js__$Boot_HaxeError("Cannot cast " + Std.string(o) + " to " + Std.string(t));
};
js_Boot.__nativeClassName = function(o) {
	var name = js_Boot.__toStr.call(o).slice(8,-1);
	if(name == "Object" || name == "Function" || name == "Math" || name == "JSON") return null;
	return name;
};
js_Boot.__isNativeObj = function(o) {
	return js_Boot.__nativeClassName(o) != null;
};
js_Boot.__resolveNativeClass = function(name) {
	return (Function("return typeof " + name + " != \"undefined\" ? " + name + " : null"))();
};
var js_html_compat_ArrayBuffer = function(a) {
	if((a instanceof Array) && a.__enum__ == null) {
		this.a = a;
		this.byteLength = a.length;
	} else {
		var len = a;
		this.a = [];
		var _g = 0;
		while(_g < len) {
			var i = _g++;
			this.a[i] = 0;
		}
		this.byteLength = len;
	}
};
$hxClasses["js.html.compat.ArrayBuffer"] = js_html_compat_ArrayBuffer;
js_html_compat_ArrayBuffer.__name__ = true;
js_html_compat_ArrayBuffer.sliceImpl = function(begin,end) {
	var u = new Uint8Array(this,begin,end == null?null:end - begin);
	var result = new ArrayBuffer(u.byteLength);
	var resultArray = new Uint8Array(result);
	resultArray.set(u);
	return result;
};
js_html_compat_ArrayBuffer.prototype = {
	slice: function(begin,end) {
		return new js_html_compat_ArrayBuffer(this.a.slice(begin,end));
	}
	,__class__: js_html_compat_ArrayBuffer
};
var js_html_compat_DataView = function(buffer,byteOffset,byteLength) {
	this.buf = buffer;
	if(byteOffset == null) this.offset = 0; else this.offset = byteOffset;
	if(byteLength == null) this.length = buffer.byteLength - this.offset; else this.length = byteLength;
	if(this.offset < 0 || this.length < 0 || this.offset + this.length > buffer.byteLength) throw new js__$Boot_HaxeError(haxe_io_Error.OutsideBounds);
};
$hxClasses["js.html.compat.DataView"] = js_html_compat_DataView;
js_html_compat_DataView.__name__ = true;
js_html_compat_DataView.prototype = {
	getInt8: function(byteOffset) {
		var v = this.buf.a[this.offset + byteOffset];
		if(v >= 128) return v - 256; else return v;
	}
	,getUint8: function(byteOffset) {
		return this.buf.a[this.offset + byteOffset];
	}
	,getInt16: function(byteOffset,littleEndian) {
		var v = this.getUint16(byteOffset,littleEndian);
		if(v >= 32768) return v - 65536; else return v;
	}
	,getUint16: function(byteOffset,littleEndian) {
		if(littleEndian) return this.buf.a[this.offset + byteOffset] | this.buf.a[this.offset + byteOffset + 1] << 8; else return this.buf.a[this.offset + byteOffset] << 8 | this.buf.a[this.offset + byteOffset + 1];
	}
	,getInt32: function(byteOffset,littleEndian) {
		var p = this.offset + byteOffset;
		var a = this.buf.a[p++];
		var b = this.buf.a[p++];
		var c = this.buf.a[p++];
		var d = this.buf.a[p++];
		if(littleEndian) return a | b << 8 | c << 16 | d << 24; else return d | c << 8 | b << 16 | a << 24;
	}
	,getUint32: function(byteOffset,littleEndian) {
		var v = this.getInt32(byteOffset,littleEndian);
		if(v < 0) return v + 4294967296.; else return v;
	}
	,getFloat32: function(byteOffset,littleEndian) {
		return haxe_io_FPHelper.i32ToFloat(this.getInt32(byteOffset,littleEndian));
	}
	,getFloat64: function(byteOffset,littleEndian) {
		var a = this.getInt32(byteOffset,littleEndian);
		var b = this.getInt32(byteOffset + 4,littleEndian);
		return haxe_io_FPHelper.i64ToDouble(littleEndian?a:b,littleEndian?b:a);
	}
	,setInt8: function(byteOffset,value) {
		if(value < 0) this.buf.a[byteOffset + this.offset] = value + 128 & 255; else this.buf.a[byteOffset + this.offset] = value & 255;
	}
	,setUint8: function(byteOffset,value) {
		this.buf.a[byteOffset + this.offset] = value & 255;
	}
	,setInt16: function(byteOffset,value,littleEndian) {
		this.setUint16(byteOffset,value < 0?value + 65536:value,littleEndian);
	}
	,setUint16: function(byteOffset,value,littleEndian) {
		var p = byteOffset + this.offset;
		if(littleEndian) {
			this.buf.a[p] = value & 255;
			this.buf.a[p++] = value >> 8 & 255;
		} else {
			this.buf.a[p++] = value >> 8 & 255;
			this.buf.a[p] = value & 255;
		}
	}
	,setInt32: function(byteOffset,value,littleEndian) {
		this.setUint32(byteOffset,value,littleEndian);
	}
	,setUint32: function(byteOffset,value,littleEndian) {
		var p = byteOffset + this.offset;
		if(littleEndian) {
			this.buf.a[p++] = value & 255;
			this.buf.a[p++] = value >> 8 & 255;
			this.buf.a[p++] = value >> 16 & 255;
			this.buf.a[p++] = value >>> 24;
		} else {
			this.buf.a[p++] = value >>> 24;
			this.buf.a[p++] = value >> 16 & 255;
			this.buf.a[p++] = value >> 8 & 255;
			this.buf.a[p++] = value & 255;
		}
	}
	,setFloat32: function(byteOffset,value,littleEndian) {
		this.setUint32(byteOffset,haxe_io_FPHelper.floatToI32(value),littleEndian);
	}
	,setFloat64: function(byteOffset,value,littleEndian) {
		var i64 = haxe_io_FPHelper.doubleToI64(value);
		if(littleEndian) {
			this.setUint32(byteOffset,i64.low);
			this.setUint32(byteOffset,i64.high);
		} else {
			this.setUint32(byteOffset,i64.high);
			this.setUint32(byteOffset,i64.low);
		}
	}
	,__class__: js_html_compat_DataView
};
var js_html_compat_Uint8Array = function() { };
$hxClasses["js.html.compat.Uint8Array"] = js_html_compat_Uint8Array;
js_html_compat_Uint8Array.__name__ = true;
js_html_compat_Uint8Array._new = function(arg1,offset,length) {
	var arr;
	if(typeof(arg1) == "number") {
		arr = [];
		var _g = 0;
		while(_g < arg1) {
			var i = _g++;
			arr[i] = 0;
		}
		arr.byteLength = arr.length;
		arr.byteOffset = 0;
		arr.buffer = new js_html_compat_ArrayBuffer(arr);
	} else if(js_Boot.__instanceof(arg1,js_html_compat_ArrayBuffer)) {
		var buffer = arg1;
		if(offset == null) offset = 0;
		if(length == null) length = buffer.byteLength - offset;
		if(offset == 0) arr = buffer.a; else arr = buffer.a.slice(offset,offset + length);
		arr.byteLength = arr.length;
		arr.byteOffset = offset;
		arr.buffer = buffer;
	} else if((arg1 instanceof Array) && arg1.__enum__ == null) {
		arr = arg1.slice();
		arr.byteLength = arr.length;
		arr.byteOffset = 0;
		arr.buffer = new js_html_compat_ArrayBuffer(arr);
	} else throw new js__$Boot_HaxeError("TODO " + Std.string(arg1));
	arr.subarray = js_html_compat_Uint8Array._subarray;
	arr.set = js_html_compat_Uint8Array._set;
	return arr;
};
js_html_compat_Uint8Array._set = function(arg,offset) {
	var t = this;
	if(js_Boot.__instanceof(arg.buffer,js_html_compat_ArrayBuffer)) {
		var a = arg;
		if(arg.byteLength + offset > t.byteLength) throw new js__$Boot_HaxeError("set() outside of range");
		var _g1 = 0;
		var _g = arg.byteLength;
		while(_g1 < _g) {
			var i = _g1++;
			t[i + offset] = a[i];
		}
	} else if((arg instanceof Array) && arg.__enum__ == null) {
		var a1 = arg;
		if(a1.length + offset > t.byteLength) throw new js__$Boot_HaxeError("set() outside of range");
		var _g11 = 0;
		var _g2 = a1.length;
		while(_g11 < _g2) {
			var i1 = _g11++;
			t[i1 + offset] = a1[i1];
		}
	} else throw new js__$Boot_HaxeError("TODO");
};
js_html_compat_Uint8Array._subarray = function(start,end) {
	var t = this;
	var a = js_html_compat_Uint8Array._new(t.slice(start,end));
	a.byteOffset = start;
	return a;
};
var lime_AssetCache = function() {
	this.enabled = true;
	this.audio = new haxe_ds_StringMap();
	this.font = new haxe_ds_StringMap();
	this.image = new haxe_ds_StringMap();
};
$hxClasses["lime.AssetCache"] = lime_AssetCache;
lime_AssetCache.__name__ = true;
lime_AssetCache.prototype = {
	clear: function(prefix) {
		if(prefix == null) {
			this.audio = new haxe_ds_StringMap();
			this.font = new haxe_ds_StringMap();
			this.image = new haxe_ds_StringMap();
		} else {
			var keys = this.audio.keys();
			while( keys.hasNext() ) {
				var key = keys.next();
				if(StringTools.startsWith(key,prefix)) this.audio.remove(key);
			}
			var keys1 = this.font.keys();
			while( keys1.hasNext() ) {
				var key1 = keys1.next();
				if(StringTools.startsWith(key1,prefix)) this.font.remove(key1);
			}
			var keys2 = this.image.keys();
			while( keys2.hasNext() ) {
				var key2 = keys2.next();
				if(StringTools.startsWith(key2,prefix)) this.image.remove(key2);
			}
		}
	}
	,__class__: lime_AssetCache
};
var lime_app_Event_$Void_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_Void_Void"] = lime_app_Event_$Void_$Void;
lime_app_Event_$Void_$Void.__name__ = true;
lime_app_Event_$Void_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function() {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i]();
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$Void_$Void
};
var lime_Assets = function() { };
$hxClasses["lime.Assets"] = lime_Assets;
lime_Assets.__name__ = true;
lime_Assets.getFont = function(id,useCache) {
	if(useCache == null) useCache = true;
	lime_Assets.initialize();
	if(useCache && lime_Assets.cache.enabled && lime_Assets.cache.font.exists(id)) return lime_Assets.cache.font.get(id);
	var libraryName = id.substring(0,id.indexOf(":"));
	var symbolName;
	var pos = id.indexOf(":") + 1;
	symbolName = HxOverrides.substr(id,pos,null);
	var library = lime_Assets.getLibrary(libraryName);
	if(library != null) {
		if(library.exists(symbolName,"FONT")) {
			if(library.isLocal(symbolName,"FONT")) {
				var font = library.getFont(symbolName);
				if(useCache && lime_Assets.cache.enabled) lime_Assets.cache.font.set(id,font);
				return font;
			} else console.log("[Assets] Font asset \"" + id + "\" exists, but only asynchronously");
		} else console.log("[Assets] There is no Font asset with an ID of \"" + id + "\"");
	} else console.log("[Assets] There is no asset library named \"" + libraryName + "\"");
	return null;
};
lime_Assets.getImage = function(id,useCache) {
	if(useCache == null) useCache = true;
	lime_Assets.initialize();
	if(useCache && lime_Assets.cache.enabled && lime_Assets.cache.image.exists(id)) {
		var image = lime_Assets.cache.image.get(id);
		if(lime_Assets.isValidImage(image)) return image;
	}
	var libraryName = id.substring(0,id.indexOf(":"));
	var symbolName;
	var pos = id.indexOf(":") + 1;
	symbolName = HxOverrides.substr(id,pos,null);
	var library = lime_Assets.getLibrary(libraryName);
	if(library != null) {
		if(library.exists(symbolName,"IMAGE")) {
			if(library.isLocal(symbolName,"IMAGE")) {
				var image1 = library.getImage(symbolName);
				if(useCache && lime_Assets.cache.enabled) lime_Assets.cache.image.set(id,image1);
				return image1;
			} else console.log("[Assets] Image asset \"" + id + "\" exists, but only asynchronously");
		} else console.log("[Assets] There is no Image asset with an ID of \"" + id + "\"");
	} else console.log("[Assets] There is no asset library named \"" + libraryName + "\"");
	return null;
};
lime_Assets.getLibrary = function(name) {
	if(name == null || name == "") name = "default";
	return lime_Assets.libraries.get(name);
};
lime_Assets.initialize = function() {
	if(!lime_Assets.initialized) {
		lime_Assets.registerLibrary("default",new DefaultAssetLibrary());
		lime_Assets.initialized = true;
	}
};
lime_Assets.isValidImage = function(buffer) {
	return true;
};
lime_Assets.registerLibrary = function(name,library) {
	if(lime_Assets.libraries.exists(name)) {
		if(lime_Assets.libraries.get(name) == library) return; else lime_Assets.unloadLibrary(name);
	}
	if(library != null) library.onChange.add(lime_Assets.library_onChange);
	lime_Assets.libraries.set(name,library);
};
lime_Assets.unloadLibrary = function(name) {
	lime_Assets.initialize();
	var library = lime_Assets.libraries.get(name);
	if(library != null) {
		lime_Assets.cache.clear(name + ":");
		library.onChange.remove(lime_Assets.library_onChange);
		library.unload();
	}
	lime_Assets.libraries.remove(name);
};
lime_Assets.library_onChange = function() {
	lime_Assets.cache.clear();
	lime_Assets.onChange.dispatch();
};
var lime__$backend_html5_HTML5Application = function(parent) {
	this.parent = parent;
	this.currentUpdate = 0;
	this.lastUpdate = 0;
	this.nextUpdate = 0;
	this.framePeriod = -1;
	lime_audio_AudioManager.init();
};
$hxClasses["lime._backend.html5.HTML5Application"] = lime__$backend_html5_HTML5Application;
lime__$backend_html5_HTML5Application.__name__ = true;
lime__$backend_html5_HTML5Application.prototype = {
	convertKeyCode: function(keyCode) {
		if(keyCode >= 65 && keyCode <= 90) return keyCode + 32;
		switch(keyCode) {
		case 16:
			return 1073742049;
		case 17:
			return 1073742048;
		case 18:
			return 1073742050;
		case 20:
			return 1073741881;
		case 144:
			return 1073741907;
		case 37:
			return 1073741904;
		case 38:
			return 1073741906;
		case 39:
			return 1073741903;
		case 40:
			return 1073741905;
		case 45:
			return 1073741897;
		case 46:
			return 127;
		case 36:
			return 1073741898;
		case 35:
			return 1073741901;
		case 33:
			return 1073741899;
		case 34:
			return 1073741902;
		case 112:
			return 1073741882;
		case 113:
			return 1073741883;
		case 114:
			return 1073741884;
		case 115:
			return 1073741885;
		case 116:
			return 1073741886;
		case 117:
			return 1073741887;
		case 118:
			return 1073741888;
		case 119:
			return 1073741889;
		case 120:
			return 1073741890;
		case 121:
			return 1073741891;
		case 122:
			return 1073741892;
		case 123:
			return 1073741893;
		case 124:
			return 1073741928;
		case 125:
			return 1073741929;
		case 126:
			return 1073741930;
		case 186:
			return 59;
		case 187:
			return 61;
		case 188:
			return 44;
		case 189:
			return 45;
		case 190:
			return 46;
		case 191:
			return 47;
		case 192:
			return 96;
		case 219:
			return 91;
		case 220:
			return 92;
		case 221:
			return 93;
		case 222:
			return 39;
		}
		return keyCode;
	}
	,create: function(config) {
	}
	,exec: function() {
		window.addEventListener("keydown",$bind(this,this.handleKeyEvent),false);
		window.addEventListener("keyup",$bind(this,this.handleKeyEvent),false);
		window.addEventListener("focus",$bind(this,this.handleWindowEvent),false);
		window.addEventListener("blur",$bind(this,this.handleWindowEvent),false);
		window.addEventListener("resize",$bind(this,this.handleWindowEvent),false);
		window.addEventListener("beforeunload",$bind(this,this.handleWindowEvent),false);
		
			var lastTime = 0;
			var vendors = ['ms', 'moz', 'webkit', 'o'];
			for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
				window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
				window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
			}
			
			if (!window.requestAnimationFrame)
				window.requestAnimationFrame = function(callback, element) {
					var currTime = new Date().getTime();
					var timeToCall = Math.max(0, 16 - (currTime - lastTime));
					var id = window.setTimeout(function() { callback(currTime + timeToCall); }, 
					  timeToCall);
					lastTime = currTime + timeToCall;
					return id;
				};
			
			if (!window.cancelAnimationFrame)
				window.cancelAnimationFrame = function(id) {
					clearTimeout(id);
				};
			
			window.requestAnimFrame = window.requestAnimationFrame;
		;
		this.lastUpdate = new Date().getTime();
		this.handleApplicationEvent();
		return 0;
	}
	,exit: function() {
	}
	,handleApplicationEvent: function(__) {
		this.currentUpdate = new Date().getTime();
		if(this.currentUpdate >= this.nextUpdate) {
			this.deltaTime = this.currentUpdate - this.lastUpdate;
			this.parent.onUpdate.dispatch(this.deltaTime | 0);
			if(this.parent.renderers[0] != null) {
				this.parent.renderers[0].onRender.dispatch();
				this.parent.renderers[0].flip();
			}
			if(this.framePeriod < 0) {
				this.nextUpdate = this.currentUpdate;
				this.nextUpdate = this.currentUpdate;
			} else this.nextUpdate = this.currentUpdate + this.framePeriod;
			this.lastUpdate = this.currentUpdate;
		}
		window.requestAnimationFrame($bind(this,this.handleApplicationEvent));
	}
	,handleKeyEvent: function(event) {
		if(this.parent.windows[0] != null) {
			var keyCode = this.convertKeyCode(event.keyCode != null?event.keyCode:event.which);
			var modifier;
			modifier = (event.shiftKey?3:0) | (event.ctrlKey?192:0) | (event.altKey?768:0) | (event.metaKey?3072:0);
			if(event.type == "keydown") this.parent.windows[0].onKeyDown.dispatch(keyCode,modifier); else this.parent.windows[0].onKeyUp.dispatch(keyCode,modifier);
		}
	}
	,handleWindowEvent: function(event) {
		if(this.parent.windows[0] != null) {
			var _g = event.type;
			switch(_g) {
			case "focus":
				this.parent.windows[0].onFocusIn.dispatch();
				this.parent.windows[0].onActivate.dispatch();
				break;
			case "blur":
				this.parent.windows[0].onFocusOut.dispatch();
				this.parent.windows[0].onDeactivate.dispatch();
				break;
			case "resize":
				var cacheWidth = this.parent.windows[0].__width;
				var cacheHeight = this.parent.windows[0].__height;
				this.parent.windows[0].backend.handleResize();
				if(this.parent.windows[0].__width != cacheWidth || this.parent.windows[0].__height != cacheHeight) this.parent.windows[0].onResize.dispatch(this.parent.windows[0].__width,this.parent.windows[0].__height);
				break;
			case "beforeunload":
				this.parent.windows[0].onClose.dispatch();
				break;
			}
		}
	}
	,setFrameRate: function(value) {
		if(value >= 60) this.framePeriod = -1; else if(value > 0) this.framePeriod = 1000 / value; else this.framePeriod = 1000;
		return value;
	}
	,__class__: lime__$backend_html5_HTML5Application
};
var lime__$backend_html5_HTML5Mouse = function() { };
$hxClasses["lime._backend.html5.HTML5Mouse"] = lime__$backend_html5_HTML5Mouse;
lime__$backend_html5_HTML5Mouse.__name__ = true;
lime__$backend_html5_HTML5Mouse.__cursor = null;
lime__$backend_html5_HTML5Mouse.__hidden = null;
lime__$backend_html5_HTML5Mouse.set_cursor = function(value) {
	if(lime__$backend_html5_HTML5Mouse.__cursor != value) {
		if(!lime__$backend_html5_HTML5Mouse.__hidden) {
			var _g = 0;
			var _g1 = lime_app_Application.current.windows;
			while(_g < _g1.length) {
				var $window = _g1[_g];
				++_g;
				switch(value[1]) {
				case 0:
					$window.backend.element.style.cursor = "default";
					break;
				case 1:
					$window.backend.element.style.cursor = "crosshair";
					break;
				case 3:
					$window.backend.element.style.cursor = "move";
					break;
				case 4:
					$window.backend.element.style.cursor = "pointer";
					break;
				case 5:
					$window.backend.element.style.cursor = "nesw-resize";
					break;
				case 6:
					$window.backend.element.style.cursor = "ns-resize";
					break;
				case 7:
					$window.backend.element.style.cursor = "nwse-resize";
					break;
				case 8:
					$window.backend.element.style.cursor = "ew-resize";
					break;
				case 9:
					$window.backend.element.style.cursor = "text";
					break;
				case 10:
					$window.backend.element.style.cursor = "wait";
					break;
				case 11:
					$window.backend.element.style.cursor = "wait";
					break;
				default:
					$window.backend.element.style.cursor = "auto";
				}
			}
		}
		lime__$backend_html5_HTML5Mouse.__cursor = value;
	}
	return lime__$backend_html5_HTML5Mouse.__cursor;
};
var lime__$backend_html5_HTML5Renderer = function(parent) {
	this.parent = parent;
};
$hxClasses["lime._backend.html5.HTML5Renderer"] = lime__$backend_html5_HTML5Renderer;
lime__$backend_html5_HTML5Renderer.__name__ = true;
lime__$backend_html5_HTML5Renderer.prototype = {
	create: function() {
		this.createContext();
		{
			var _g = this.parent.context;
			switch(_g[1]) {
			case 0:
				this.parent.window.backend.canvas.addEventListener("webglcontextlost",$bind(this,this.handleEvent),false);
				this.parent.window.backend.canvas.addEventListener("webglcontextrestored",$bind(this,this.handleEvent),false);
				break;
			default:
			}
		}
	}
	,createContext: function() {
		if(this.parent.window.backend.div != null) {
			this.parent.context = lime_graphics_RenderContext.DOM(this.parent.window.backend.div);
			this.parent.type = lime_graphics_RendererType.DOM;
		} else if(this.parent.window.backend.canvas != null) {
			var webgl = null;
			if(webgl == null) {
				this.parent.context = lime_graphics_RenderContext.CANVAS(this.parent.window.backend.canvas.getContext("2d"));
				this.parent.type = lime_graphics_RendererType.CANVAS;
			} else {
				lime_graphics_opengl_GL.context = webgl;
				this.parent.context = lime_graphics_RenderContext.OPENGL(lime_graphics_opengl_GL.context);
				this.parent.type = lime_graphics_RendererType.OPENGL;
			}
		}
	}
	,flip: function() {
	}
	,handleEvent: function(event) {
		var _g = event.type;
		switch(_g) {
		case "webglcontextlost":
			event.preventDefault();
			this.parent.context = null;
			this.parent.onContextLost.dispatch();
			break;
		case "webglcontextrestored":
			this.createContext();
			this.parent.onContextRestored.dispatch(this.parent.context);
			break;
		default:
		}
	}
	,__class__: lime__$backend_html5_HTML5Renderer
};
var lime__$backend_html5_HTML5Window = function(parent) {
	this.unusedTouchesPool = new List();
	this.currentTouches = new haxe_ds_IntMap();
	this.parent = parent;
	if(parent.config != null && Object.prototype.hasOwnProperty.call(parent.config,"element")) this.element = parent.config.element;
};
$hxClasses["lime._backend.html5.HTML5Window"] = lime__$backend_html5_HTML5Window;
lime__$backend_html5_HTML5Window.__name__ = true;
lime__$backend_html5_HTML5Window.textInput = null;
lime__$backend_html5_HTML5Window.prototype = {
	close: function() {
		this.parent.application.removeWindow(this.parent);
	}
	,create: function(application) {
		this.setWidth = this.parent.__width;
		this.setHeight = this.parent.__height;
		this.parent.id = lime__$backend_html5_HTML5Window.windowID++;
		if(js_Boot.__instanceof(this.element,HTMLCanvasElement)) this.canvas = this.element; else this.canvas = window.document.createElement("canvas");
		if(this.canvas != null) {
			var style = this.canvas.style;
			style.setProperty("-webkit-transform","translateZ(0)",null);
			style.setProperty("transform","translateZ(0)",null);
		} else if(this.div != null) {
			var style1 = this.div.style;
			style1.setProperty("-webkit-transform","translate3D(0,0,0)",null);
			style1.setProperty("transform","translate3D(0,0,0)",null);
			style1.position = "relative";
			style1.overflow = "hidden";
			style1.setProperty("-webkit-user-select","none",null);
			style1.setProperty("-moz-user-select","none",null);
			style1.setProperty("-ms-user-select","none",null);
			style1.setProperty("-o-user-select","none",null);
		}
		if(this.parent.__width == 0 && this.parent.__height == 0) {
			if(this.element != null) {
				this.parent.set_width(this.element.clientWidth);
				this.parent.set_height(this.element.clientHeight);
			} else {
				this.parent.set_width(window.innerWidth);
				this.parent.set_height(window.innerHeight);
			}
			this.parent.set_fullscreen(true);
		}
		if(this.canvas != null) {
			this.canvas.width = this.parent.__width;
			this.canvas.height = this.parent.__height;
		} else {
			this.div.style.width = this.parent.__width + "px";
			this.div.style.height = this.parent.__height + "px";
		}
		this.handleResize();
		if(this.element != null) {
			if(this.canvas != null) {
				if(this.element != this.canvas) this.element.appendChild(this.canvas);
			} else this.element.appendChild(this.div);
			var events = ["mousedown","mouseenter","mouseleave","mousemove","mouseup","wheel"];
			var _g = 0;
			while(_g < events.length) {
				var event = events[_g];
				++_g;
				this.element.addEventListener(event,$bind(this,this.handleMouseEvent),true);
			}
			window.document.addEventListener("dragstart",function(e) {
				if(e.target.nodeName.toLowerCase() == "img") {
					e.preventDefault();
					return false;
				}
				return true;
			},false);
			this.element.addEventListener("touchstart",$bind(this,this.handleTouchEvent),true);
			this.element.addEventListener("touchmove",$bind(this,this.handleTouchEvent),true);
			this.element.addEventListener("touchend",$bind(this,this.handleTouchEvent),true);
		}
	}
	,handleFocusEvent: function(event) {
		if(this.enableTextEvents) haxe_Timer.delay(function() {
			lime__$backend_html5_HTML5Window.textInput.focus();
		},20);
	}
	,handleInputEvent: function(event) {
		if(lime__$backend_html5_HTML5Window.textInput.value != "") {
			this.parent.onTextInput.dispatch(lime__$backend_html5_HTML5Window.textInput.value);
			lime__$backend_html5_HTML5Window.textInput.value = "";
		}
	}
	,handleMouseEvent: function(event) {
		var x = 0.0;
		var y = 0.0;
		if(event.type != "wheel") {
			if(this.element != null) {
				if(this.canvas != null) {
					var rect = this.canvas.getBoundingClientRect();
					x = (event.clientX - rect.left) * (this.parent.__width / rect.width);
					y = (event.clientY - rect.top) * (this.parent.__height / rect.height);
				} else if(this.div != null) {
					var rect1 = this.div.getBoundingClientRect();
					x = event.clientX - rect1.left;
					y = event.clientY - rect1.top;
				} else {
					var rect2 = this.element.getBoundingClientRect();
					x = (event.clientX - rect2.left) * (this.parent.__width / rect2.width);
					y = (event.clientY - rect2.top) * (this.parent.__height / rect2.height);
				}
			} else {
				x = event.clientX;
				y = event.clientY;
			}
			var _g = event.type;
			switch(_g) {
			case "mousedown":
				this.parent.onMouseDown.dispatch(x,y,event.button);
				break;
			case "mouseenter":
				this.parent.onEnter.dispatch();
				break;
			case "mouseleave":
				this.parent.onLeave.dispatch();
				break;
			case "mouseup":
				this.parent.onMouseUp.dispatch(x,y,event.button);
				break;
			case "mousemove":
				this.parent.onMouseMove.dispatch(x,y);
				break;
			default:
			}
		} else this.parent.onMouseWheel.dispatch(event.deltaX,-event.deltaY);
	}
	,handleResize: function() {
		var stretch = this.parent.__fullscreen || this.setWidth == 0 && this.setHeight == 0;
		if(this.element != null && (this.div == null || this.div != null && stretch)) {
			if(stretch) {
				if(this.parent.__width != this.element.clientWidth || this.parent.__height != this.element.clientHeight) {
					this.parent.set_width(this.element.clientWidth);
					this.parent.set_height(this.element.clientHeight);
					if(this.canvas != null) {
						if(this.element != this.canvas) {
							this.canvas.width = this.element.clientWidth;
							this.canvas.height = this.element.clientHeight;
						}
					} else {
						this.div.style.width = this.element.clientWidth + "px";
						this.div.style.height = this.element.clientHeight + "px";
					}
				}
			} else {
				var scaleX = this.element.clientWidth / this.setWidth;
				var scaleY = this.element.clientHeight / this.setHeight;
				var currentRatio = scaleX / scaleY;
				var targetRatio = Math.min(scaleX,scaleY);
				if(this.canvas != null) {
					if(this.element != this.canvas) {
						this.canvas.style.width = this.setWidth * targetRatio + "px";
						this.canvas.style.height = this.setHeight * targetRatio + "px";
						this.canvas.style.marginLeft = (this.element.clientWidth - this.setWidth * targetRatio) / 2 + "px";
						this.canvas.style.marginTop = (this.element.clientHeight - this.setHeight * targetRatio) / 2 + "px";
					}
				} else {
					this.div.style.width = this.setWidth * targetRatio + "px";
					this.div.style.height = this.setHeight * targetRatio + "px";
					this.div.style.marginLeft = (this.element.clientWidth - this.setWidth * targetRatio) / 2 + "px";
					this.div.style.marginTop = (this.element.clientHeight - this.setHeight * targetRatio) / 2 + "px";
				}
			}
		}
	}
	,handleTouchEvent: function(event) {
		event.preventDefault();
		var rect = null;
		if(this.element != null) {
			if(this.canvas != null) rect = this.canvas.getBoundingClientRect(); else if(this.div != null) rect = this.div.getBoundingClientRect(); else rect = this.element.getBoundingClientRect();
		}
		var _g = 0;
		var _g1 = event.changedTouches;
		while(_g < _g1.length) {
			var data = _g1[_g];
			++_g;
			var x = 0.0;
			var y = 0.0;
			if(rect != null) {
				x = (data.clientX - rect.left) * (this.parent.__width / rect.width);
				y = (data.clientY - rect.top) * (this.parent.__height / rect.height);
			} else {
				x = data.clientX;
				y = data.clientY;
			}
			var _g2 = event.type;
			switch(_g2) {
			case "touchstart":
				var touch = this.unusedTouchesPool.pop();
				if(touch == null) touch = new lime_ui_Touch(x / this.setWidth,y / this.setHeight,data.identifier,0,0,data.force,this.parent.id); else {
					touch.x = x / this.setWidth;
					touch.y = y / this.setHeight;
					touch.id = data.identifier;
					touch.dx = 0;
					touch.dy = 0;
					touch.pressure = data.force;
					touch.device = this.parent.id;
				}
				this.currentTouches.h[data.identifier] = touch;
				lime_ui_Touch.onStart.dispatch(touch);
				if(this.primaryTouch == null) this.primaryTouch = touch;
				if(touch == this.primaryTouch) this.parent.onMouseDown.dispatch(x,y,0);
				break;
			case "touchend":
				var touch1 = this.currentTouches.h[data.identifier];
				if(touch1 != null) {
					var cacheX = touch1.x;
					var cacheY = touch1.y;
					touch1.x = x / this.setWidth;
					touch1.y = y / this.setHeight;
					touch1.dx = touch1.x - cacheX;
					touch1.dy = touch1.y - cacheY;
					touch1.pressure = data.force;
					lime_ui_Touch.onEnd.dispatch(touch1);
					this.currentTouches.remove(data.identifier);
					this.unusedTouchesPool.add(touch1);
					if(touch1 == this.primaryTouch) {
						this.parent.onMouseUp.dispatch(x,y,0);
						this.primaryTouch = null;
					}
				}
				break;
			case "touchmove":
				var touch2 = this.currentTouches.h[data.identifier];
				if(touch2 != null) {
					var cacheX1 = touch2.x;
					var cacheY1 = touch2.y;
					touch2.x = x / this.setWidth;
					touch2.y = y / this.setHeight;
					touch2.dx = touch2.x - cacheX1;
					touch2.dy = touch2.y - cacheY1;
					touch2.pressure = data.force;
					lime_ui_Touch.onMove.dispatch(touch2);
					if(touch2 == this.primaryTouch) this.parent.onMouseMove.dispatch(x,y);
				}
				break;
			default:
			}
		}
	}
	,resize: function(width,height) {
	}
	,setEnableTextEvents: function(value) {
		if(value) {
			if(lime__$backend_html5_HTML5Window.textInput == null) {
				lime__$backend_html5_HTML5Window.textInput = window.document.createElement("input");
				lime__$backend_html5_HTML5Window.textInput.type = "text";
				lime__$backend_html5_HTML5Window.textInput.style.position = "fixed";
				lime__$backend_html5_HTML5Window.textInput.style.opacity = "0";
				lime__$backend_html5_HTML5Window.textInput.style.color = "transparent";
				lime__$backend_html5_HTML5Window.textInput.value = "";
				lime__$backend_html5_HTML5Window.textInput.autocapitalize = "off";
				lime__$backend_html5_HTML5Window.textInput.autocorrect = "off";
				lime__$backend_html5_HTML5Window.textInput.autocomplete = "off";
				lime__$backend_html5_HTML5Window.textInput.style.left = "0px";
				lime__$backend_html5_HTML5Window.textInput.style.top = "50%";
				if(new EReg("(iPad|iPhone|iPod).*OS 8_","gi").match(window.navigator.userAgent)) {
					lime__$backend_html5_HTML5Window.textInput.style.fontSize = "0px";
					lime__$backend_html5_HTML5Window.textInput.style.width = "0px";
					lime__$backend_html5_HTML5Window.textInput.style.height = "0px";
				} else {
					lime__$backend_html5_HTML5Window.textInput.style.width = "1px";
					lime__$backend_html5_HTML5Window.textInput.style.height = "1px";
				}
				lime__$backend_html5_HTML5Window.textInput.style.pointerEvents = "none";
				lime__$backend_html5_HTML5Window.textInput.style.zIndex = "-10000000";
				window.document.body.appendChild(lime__$backend_html5_HTML5Window.textInput);
			}
			if(!this.enableTextEvents) {
				lime__$backend_html5_HTML5Window.textInput.addEventListener("input",$bind(this,this.handleInputEvent),true);
				lime__$backend_html5_HTML5Window.textInput.addEventListener("blur",$bind(this,this.handleFocusEvent),true);
			}
			lime__$backend_html5_HTML5Window.textInput.focus();
		} else if(lime__$backend_html5_HTML5Window.textInput != null) {
			lime__$backend_html5_HTML5Window.textInput.removeEventListener("input",$bind(this,this.handleInputEvent),true);
			lime__$backend_html5_HTML5Window.textInput.removeEventListener("blur",$bind(this,this.handleFocusEvent),true);
			lime__$backend_html5_HTML5Window.textInput.blur();
		}
		return this.enableTextEvents = value;
	}
	,setFullscreen: function(value) {
		return false;
	}
	,__class__: lime__$backend_html5_HTML5Window
};
var lime_app_IModule = function() { };
$hxClasses["lime.app.IModule"] = lime_app_IModule;
lime_app_IModule.__name__ = true;
lime_app_IModule.prototype = {
	__class__: lime_app_IModule
};
var lime_app_Module = function() {
	this.onExit = new lime_app_Event_$Int_$Void();
};
$hxClasses["lime.app.Module"] = lime_app_Module;
lime_app_Module.__name__ = true;
lime_app_Module.__interfaces__ = [lime_app_IModule];
lime_app_Module.prototype = {
	onGamepadAxisMove: function(gamepad,axis,value) {
	}
	,onGamepadButtonDown: function(gamepad,button) {
	}
	,onGamepadButtonUp: function(gamepad,button) {
	}
	,onGamepadConnect: function(gamepad) {
	}
	,onGamepadDisconnect: function(gamepad) {
	}
	,onJoystickAxisMove: function(joystick,axis,value) {
	}
	,onJoystickButtonDown: function(joystick,button) {
	}
	,onJoystickButtonUp: function(joystick,button) {
	}
	,onJoystickConnect: function(joystick) {
	}
	,onJoystickDisconnect: function(joystick) {
	}
	,onJoystickHatMove: function(joystick,hat,position) {
	}
	,onJoystickTrackballMove: function(joystick,trackball,value) {
	}
	,onKeyDown: function(window,keyCode,modifier) {
	}
	,onKeyUp: function(window,keyCode,modifier) {
	}
	,onModuleExit: function(code) {
	}
	,onMouseDown: function(window,x,y,button) {
	}
	,onMouseMove: function(window,x,y) {
	}
	,onMouseMoveRelative: function(window,x,y) {
	}
	,onMouseUp: function(window,x,y,button) {
	}
	,onMouseWheel: function(window,deltaX,deltaY) {
	}
	,onPreloadComplete: function() {
	}
	,onPreloadProgress: function(loaded,total) {
	}
	,onRenderContextLost: function(renderer) {
	}
	,onRenderContextRestored: function(renderer,context) {
	}
	,onTextEdit: function(window,text,start,length) {
	}
	,onTextInput: function(window,text) {
	}
	,onTouchEnd: function(touch) {
	}
	,onTouchMove: function(touch) {
	}
	,onTouchStart: function(touch) {
	}
	,onWindowActivate: function(window) {
	}
	,onWindowClose: function(window) {
	}
	,onWindowCreate: function(window) {
	}
	,onWindowDeactivate: function(window) {
	}
	,onWindowEnter: function(window) {
	}
	,onWindowFocusIn: function(window) {
	}
	,onWindowFocusOut: function(window) {
	}
	,onWindowFullscreen: function(window) {
	}
	,onWindowLeave: function(window) {
	}
	,onWindowMove: function(window,x,y) {
	}
	,onWindowMinimize: function(window) {
	}
	,onWindowResize: function(window,width,height) {
	}
	,onWindowRestore: function(window) {
	}
	,render: function(renderer) {
	}
	,update: function(deltaTime) {
	}
	,__class__: lime_app_Module
};
var lime_app_Application = function() {
	this.onUpdate = new lime_app_Event_$Int_$Void();
	lime_app_Module.call(this);
	if(lime_app_Application.current == null) lime_app_Application.current = this;
	this.modules = [];
	this.renderers = [];
	this.windows = [];
	this.windowByID = new haxe_ds_IntMap();
	this.backend = new lime__$backend_html5_HTML5Application(this);
	this.onExit.add($bind(this,this.onModuleExit));
	this.onUpdate.add($bind(this,this.update));
	lime_ui_Gamepad.onConnect.add($bind(this,this.onGamepadConnect));
	lime_ui_Joystick.onConnect.add($bind(this,this.onJoystickConnect));
	lime_ui_Touch.onStart.add($bind(this,this.onTouchStart));
	lime_ui_Touch.onMove.add($bind(this,this.onTouchMove));
	lime_ui_Touch.onEnd.add($bind(this,this.onTouchEnd));
};
$hxClasses["lime.app.Application"] = lime_app_Application;
lime_app_Application.__name__ = true;
lime_app_Application.current = null;
lime_app_Application.__super__ = lime_app_Module;
lime_app_Application.prototype = $extend(lime_app_Module.prototype,{
	addModule: function(module) {
		this.modules.push(module);
		if(this.windows.length > 0) {
			var _g = 0;
			var _g1 = this.windows;
			while(_g < _g1.length) {
				var $window = _g1[_g];
				++_g;
				module.onWindowCreate($window);
			}
			if(this.preloader == null || this.preloader.complete) module.onPreloadComplete();
		}
	}
	,addRenderer: function(renderer) {
		renderer.onRender.add((function(f,a1) {
			return function() {
				f(a1);
			};
		})($bind(this,this.render),renderer));
		renderer.onContextLost.add((function(f1,a11) {
			return function() {
				f1(a11);
			};
		})($bind(this,this.onRenderContextLost),renderer));
		renderer.onContextRestored.add((function(f2,a12) {
			return function(a2) {
				f2(a12,a2);
			};
		})($bind(this,this.onRenderContextRestored),renderer));
		this.renderers.push(renderer);
	}
	,createWindow: function(window) {
		window.onActivate.add((function(f,a1) {
			return function() {
				f(a1);
			};
		})($bind(this,this.onWindowActivate),window));
		window.onClose.add((function(f1,a11) {
			return function() {
				f1(a11);
			};
		})($bind(this,this.onWindowClose),window));
		window.onCreate.add((function(f2,a12) {
			return function() {
				f2(a12);
			};
		})($bind(this,this.onWindowCreate),window));
		window.onDeactivate.add((function(f3,a13) {
			return function() {
				f3(a13);
			};
		})($bind(this,this.onWindowDeactivate),window));
		window.onEnter.add((function(f4,a14) {
			return function() {
				f4(a14);
			};
		})($bind(this,this.onWindowEnter),window));
		window.onFocusIn.add((function(f5,a15) {
			return function() {
				f5(a15);
			};
		})($bind(this,this.onWindowFocusIn),window));
		window.onFocusOut.add((function(f6,a16) {
			return function() {
				f6(a16);
			};
		})($bind(this,this.onWindowFocusOut),window));
		window.onFullscreen.add((function(f7,a17) {
			return function() {
				f7(a17);
			};
		})($bind(this,this.onWindowFullscreen),window));
		window.onKeyDown.add((function(f8,a18) {
			return function(a2,a3) {
				f8(a18,a2,a3);
			};
		})($bind(this,this.onKeyDown),window));
		window.onKeyUp.add((function(f9,a19) {
			return function(a21,a31) {
				f9(a19,a21,a31);
			};
		})($bind(this,this.onKeyUp),window));
		window.onLeave.add((function(f10,a110) {
			return function() {
				f10(a110);
			};
		})($bind(this,this.onWindowLeave),window));
		window.onMinimize.add((function(f11,a111) {
			return function() {
				f11(a111);
			};
		})($bind(this,this.onWindowMinimize),window));
		window.onMouseDown.add((function(f12,a112) {
			return function(x,y,a22) {
				f12(a112,x,y,a22);
			};
		})($bind(this,this.onMouseDown),window));
		window.onMouseMove.add((function(f13,a113) {
			return function(x1,y1) {
				f13(a113,x1,y1);
			};
		})($bind(this,this.onMouseMove),window));
		window.onMouseMoveRelative.add((function(f14,a114) {
			return function(x2,y2) {
				f14(a114,x2,y2);
			};
		})($bind(this,this.onMouseMoveRelative),window));
		window.onMouseUp.add((function(f15,a115) {
			return function(x3,y3,a23) {
				f15(a115,x3,y3,a23);
			};
		})($bind(this,this.onMouseUp),window));
		window.onMouseWheel.add((function(f16,a116) {
			return function(a24,a32) {
				f16(a116,a24,a32);
			};
		})($bind(this,this.onMouseWheel),window));
		window.onMove.add((function(f17,a117) {
			return function(x4,y4) {
				f17(a117,x4,y4);
			};
		})($bind(this,this.onWindowMove),window));
		window.onResize.add((function(f18,a118) {
			return function(a25,a33) {
				f18(a118,a25,a33);
			};
		})($bind(this,this.onWindowResize),window));
		window.onRestore.add((function(f19,a119) {
			return function() {
				f19(a119);
			};
		})($bind(this,this.onWindowRestore),window));
		window.onTextEdit.add((function(f20,a120) {
			return function(a26,a34,a4) {
				f20(a120,a26,a34,a4);
			};
		})($bind(this,this.onTextEdit),window));
		window.onTextInput.add((function(f21,a121) {
			return function(a27) {
				f21(a121,a27);
			};
		})($bind(this,this.onTextInput),window));
		if(window.renderer == null) {
			var renderer = new lime_graphics_Renderer(window);
			this.addRenderer(renderer);
		}
		window.create(this);
		this.windows.push(window);
		this.windowByID.h[window.id] = window;
		window.onCreate.dispatch();
	}
	,exec: function() {
		lime_app_Application.current = this;
		return this.backend.exec();
	}
	,onGamepadAxisMove: function(gamepad,axis,value) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onGamepadAxisMove(gamepad,axis,value);
		}
	}
	,onGamepadButtonDown: function(gamepad,button) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onGamepadButtonDown(gamepad,button);
		}
	}
	,onGamepadButtonUp: function(gamepad,button) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onGamepadButtonUp(gamepad,button);
		}
	}
	,onGamepadConnect: function(gamepad) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onGamepadConnect(gamepad);
		}
		gamepad.onAxisMove.add((function(f,a1) {
			return function(a2,a3) {
				f(a1,a2,a3);
			};
		})($bind(this,this.onGamepadAxisMove),gamepad));
		gamepad.onButtonDown.add((function(f1,a11) {
			return function(a21) {
				f1(a11,a21);
			};
		})($bind(this,this.onGamepadButtonDown),gamepad));
		gamepad.onButtonUp.add((function(f2,a12) {
			return function(a22) {
				f2(a12,a22);
			};
		})($bind(this,this.onGamepadButtonUp),gamepad));
		gamepad.onDisconnect.add((function(f3,a13) {
			return function() {
				f3(a13);
			};
		})($bind(this,this.onGamepadDisconnect),gamepad));
	}
	,onGamepadDisconnect: function(gamepad) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onGamepadDisconnect(gamepad);
		}
	}
	,onJoystickAxisMove: function(joystick,axis,value) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onJoystickAxisMove(joystick,axis,value);
		}
	}
	,onJoystickButtonDown: function(joystick,button) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onJoystickButtonDown(joystick,button);
		}
	}
	,onJoystickButtonUp: function(joystick,button) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onJoystickButtonUp(joystick,button);
		}
	}
	,onJoystickConnect: function(joystick) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onJoystickConnect(joystick);
		}
		joystick.onAxisMove.add((function(f,a1) {
			return function(a2,a3) {
				f(a1,a2,a3);
			};
		})($bind(this,this.onJoystickAxisMove),joystick));
		joystick.onButtonDown.add((function(f1,a11) {
			return function(a21) {
				f1(a11,a21);
			};
		})($bind(this,this.onJoystickButtonDown),joystick));
		joystick.onButtonUp.add((function(f2,a12) {
			return function(a22) {
				f2(a12,a22);
			};
		})($bind(this,this.onJoystickButtonUp),joystick));
		joystick.onDisconnect.add((function(f3,a13) {
			return function() {
				f3(a13);
			};
		})($bind(this,this.onJoystickDisconnect),joystick));
		joystick.onHatMove.add((function(f4,a14) {
			return function(a23,a31) {
				f4(a14,a23,a31);
			};
		})($bind(this,this.onJoystickHatMove),joystick));
		joystick.onTrackballMove.add((function(f5,a15) {
			return function(a24,a32) {
				f5(a15,a24,a32);
			};
		})($bind(this,this.onJoystickTrackballMove),joystick));
	}
	,onJoystickDisconnect: function(joystick) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onJoystickDisconnect(joystick);
		}
	}
	,onJoystickHatMove: function(joystick,hat,position) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onJoystickHatMove(joystick,hat,position);
		}
	}
	,onJoystickTrackballMove: function(joystick,trackball,value) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onJoystickTrackballMove(joystick,trackball,value);
		}
	}
	,onKeyDown: function(window,keyCode,modifier) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onKeyDown(window,keyCode,modifier);
		}
	}
	,onKeyUp: function(window,keyCode,modifier) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onKeyUp(window,keyCode,modifier);
		}
	}
	,onModuleExit: function(code) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onModuleExit(code);
		}
		this.backend.exit();
	}
	,onMouseDown: function(window,x,y,button) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onMouseDown(window,x,y,button);
		}
	}
	,onMouseMove: function(window,x,y) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onMouseMove(window,x,y);
		}
	}
	,onMouseMoveRelative: function(window,x,y) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onMouseMoveRelative(window,x,y);
		}
	}
	,onMouseUp: function(window,x,y,button) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onMouseUp(window,x,y,button);
		}
	}
	,onMouseWheel: function(window,deltaX,deltaY) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onMouseWheel(window,deltaX,deltaY);
		}
	}
	,onPreloadComplete: function() {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onPreloadComplete();
		}
	}
	,onPreloadProgress: function(loaded,total) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onPreloadProgress(loaded,total);
		}
	}
	,onRenderContextLost: function(renderer) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onRenderContextLost(renderer);
		}
	}
	,onRenderContextRestored: function(renderer,context) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onRenderContextRestored(renderer,context);
		}
	}
	,onTextEdit: function(window,text,start,length) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onTextEdit(window,text,start,length);
		}
	}
	,onTextInput: function(window,text) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onTextInput(window,text);
		}
	}
	,onTouchEnd: function(touch) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onTouchEnd(touch);
		}
	}
	,onTouchMove: function(touch) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onTouchMove(touch);
		}
	}
	,onTouchStart: function(touch) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onTouchStart(touch);
		}
	}
	,onWindowActivate: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowActivate(window);
		}
	}
	,onWindowClose: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowClose(window);
		}
		this.removeWindow(window);
	}
	,onWindowCreate: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowCreate(window);
		}
	}
	,onWindowDeactivate: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowDeactivate(window);
		}
	}
	,onWindowEnter: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowEnter(window);
		}
	}
	,onWindowFocusIn: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowFocusIn(window);
		}
	}
	,onWindowFocusOut: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowFocusOut(window);
		}
	}
	,onWindowFullscreen: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowFullscreen(window);
		}
	}
	,onWindowLeave: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowLeave(window);
		}
	}
	,onWindowMinimize: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowMinimize(window);
		}
	}
	,onWindowMove: function(window,x,y) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowMove(window,x,y);
		}
	}
	,onWindowResize: function(window,width,height) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowResize(window,width,height);
		}
	}
	,onWindowRestore: function(window) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.onWindowRestore(window);
		}
	}
	,removeWindow: function(window) {
		if(window != null && this.windowByID.h.hasOwnProperty(window.id)) {
			HxOverrides.remove(this.windows,window);
			this.windowByID.remove(window.id);
			window.close();
			if(this.windows[0] == window) this.window = null;
		}
	}
	,render: function(renderer) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.render(renderer);
		}
	}
	,setPreloader: function(preloader) {
		if(this.preloader != null) {
			this.preloader.onProgress.remove($bind(this,this.onPreloadProgress));
			this.preloader.onComplete.remove($bind(this,this.onPreloadComplete));
		}
		this.preloader = preloader;
		if(preloader.complete) this.onPreloadComplete(); else {
			preloader.onProgress.add($bind(this,this.onPreloadProgress));
			preloader.onComplete.add($bind(this,this.onPreloadComplete));
		}
	}
	,update: function(deltaTime) {
		var _g = 0;
		var _g1 = this.modules;
		while(_g < _g1.length) {
			var module = _g1[_g];
			++_g;
			module.update(deltaTime);
		}
	}
	,__class__: lime_app_Application
});
var lime_app_Event_$Float_$Float_$Int_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_Float_Float_Int_Void"] = lime_app_Event_$Float_$Float_$Int_$Void;
lime_app_Event_$Float_$Float_$Int_$Void.__name__ = true;
lime_app_Event_$Float_$Float_$Int_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a,a1,a2) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a,a1,a2);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$Float_$Float_$Int_$Void
};
var lime_app_Event_$Float_$Float_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_Float_Float_Void"] = lime_app_Event_$Float_$Float_$Void;
lime_app_Event_$Float_$Float_$Void.__name__ = true;
lime_app_Event_$Float_$Float_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a,a1) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a,a1);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$Float_$Float_$Void
};
var lime_app_Event_$Int_$Float_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_Int_Float_Void"] = lime_app_Event_$Int_$Float_$Void;
lime_app_Event_$Int_$Float_$Void.__name__ = true;
lime_app_Event_$Int_$Float_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,__class__: lime_app_Event_$Int_$Float_$Void
};
var lime_app_Event_$Int_$Int_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_Int_Int_Void"] = lime_app_Event_$Int_$Int_$Void;
lime_app_Event_$Int_$Int_$Void.__name__ = true;
lime_app_Event_$Int_$Int_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a,a1) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a,a1);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$Int_$Int_$Void
};
var lime_app_Event_$Int_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_Int_Void"] = lime_app_Event_$Int_$Void;
lime_app_Event_$Int_$Void.__name__ = true;
lime_app_Event_$Int_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$Int_$Void
};
var lime_app_Event_$Int_$lime_$ui_$JoystickHatPosition_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_Int_lime_ui_JoystickHatPosition_Void"] = lime_app_Event_$Int_$lime_$ui_$JoystickHatPosition_$Void;
lime_app_Event_$Int_$lime_$ui_$JoystickHatPosition_$Void.__name__ = true;
lime_app_Event_$Int_$lime_$ui_$JoystickHatPosition_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,__class__: lime_app_Event_$Int_$lime_$ui_$JoystickHatPosition_$Void
};
var lime_app_Event_$String_$Int_$Int_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_String_Int_Int_Void"] = lime_app_Event_$String_$Int_$Int_$Void;
lime_app_Event_$String_$Int_$Int_$Void.__name__ = true;
lime_app_Event_$String_$Int_$Int_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,__class__: lime_app_Event_$String_$Int_$Int_$Void
};
var lime_app_Event_$String_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_String_Void"] = lime_app_Event_$String_$Void;
lime_app_Event_$String_$Void.__name__ = true;
lime_app_Event_$String_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,has: function(listener) {
		var _g = 0;
		var _g1 = this.listeners;
		while(_g < _g1.length) {
			var l = _g1[_g];
			++_g;
			if(Reflect.compareMethods(l,listener)) return true;
		}
		return false;
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$String_$Void
};
var lime_app_Event_$lime_$graphics_$RenderContext_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_graphics_RenderContext_Void"] = lime_app_Event_$lime_$graphics_$RenderContext_$Void;
lime_app_Event_$lime_$graphics_$RenderContext_$Void.__name__ = true;
lime_app_Event_$lime_$graphics_$RenderContext_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$lime_$graphics_$RenderContext_$Void
};
var lime_app_Event_$lime_$net_$URLLoader_$Int_$Int_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_net_URLLoader_Int_Int_Void"] = lime_app_Event_$lime_$net_$URLLoader_$Int_$Int_$Void;
lime_app_Event_$lime_$net_$URLLoader_$Int_$Int_$Void.__name__ = true;
lime_app_Event_$lime_$net_$URLLoader_$Int_$Int_$Void.prototype = {
	remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a,a1,a2) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a,a1,a2);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$lime_$net_$URLLoader_$Int_$Int_$Void
};
var lime_app_Event_$lime_$net_$URLLoader_$Int_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_net_URLLoader_Int_Void"] = lime_app_Event_$lime_$net_$URLLoader_$Int_$Void;
lime_app_Event_$lime_$net_$URLLoader_$Int_$Void.__name__ = true;
lime_app_Event_$lime_$net_$URLLoader_$Int_$Void.prototype = {
	remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a,a1) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a,a1);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$lime_$net_$URLLoader_$Int_$Void
};
var lime_app_Event_$lime_$net_$URLLoader_$String_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_net_URLLoader_String_Void"] = lime_app_Event_$lime_$net_$URLLoader_$String_$Void;
lime_app_Event_$lime_$net_$URLLoader_$String_$Void.__name__ = true;
lime_app_Event_$lime_$net_$URLLoader_$String_$Void.prototype = {
	remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a,a1) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a,a1);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$lime_$net_$URLLoader_$String_$Void
};
var lime_app_Event_$lime_$net_$URLLoader_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_net_URLLoader_Void"] = lime_app_Event_$lime_$net_$URLLoader_$Void;
lime_app_Event_$lime_$net_$URLLoader_$Void.__name__ = true;
lime_app_Event_$lime_$net_$URLLoader_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$lime_$net_$URLLoader_$Void
};
var lime_app_Event_$lime_$ui_$GamepadAxis_$Float_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_ui_GamepadAxis_Float_Void"] = lime_app_Event_$lime_$ui_$GamepadAxis_$Float_$Void;
lime_app_Event_$lime_$ui_$GamepadAxis_$Float_$Void.__name__ = true;
lime_app_Event_$lime_$ui_$GamepadAxis_$Float_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,__class__: lime_app_Event_$lime_$ui_$GamepadAxis_$Float_$Void
};
var lime_app_Event_$lime_$ui_$GamepadButton_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_ui_GamepadButton_Void"] = lime_app_Event_$lime_$ui_$GamepadButton_$Void;
lime_app_Event_$lime_$ui_$GamepadButton_$Void.__name__ = true;
lime_app_Event_$lime_$ui_$GamepadButton_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,__class__: lime_app_Event_$lime_$ui_$GamepadButton_$Void
};
var lime_app_Event_$lime_$ui_$Gamepad_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_ui_Gamepad_Void"] = lime_app_Event_$lime_$ui_$Gamepad_$Void;
lime_app_Event_$lime_$ui_$Gamepad_$Void.__name__ = true;
lime_app_Event_$lime_$ui_$Gamepad_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,__class__: lime_app_Event_$lime_$ui_$Gamepad_$Void
};
var lime_app_Event_$lime_$ui_$Joystick_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_ui_Joystick_Void"] = lime_app_Event_$lime_$ui_$Joystick_$Void;
lime_app_Event_$lime_$ui_$Joystick_$Void.__name__ = true;
lime_app_Event_$lime_$ui_$Joystick_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,__class__: lime_app_Event_$lime_$ui_$Joystick_$Void
};
var lime_app_Event_$lime_$ui_$KeyCode_$lime_$ui_$KeyModifier_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_ui_KeyCode_lime_ui_KeyModifier_Void"] = lime_app_Event_$lime_$ui_$KeyCode_$lime_$ui_$KeyModifier_$Void;
lime_app_Event_$lime_$ui_$KeyCode_$lime_$ui_$KeyModifier_$Void.__name__ = true;
lime_app_Event_$lime_$ui_$KeyCode_$lime_$ui_$KeyModifier_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a,a1) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a,a1);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$lime_$ui_$KeyCode_$lime_$ui_$KeyModifier_$Void
};
var lime_app_Event_$lime_$ui_$Touch_$Void = function() {
	this.listeners = [];
	this.priorities = [];
	this.repeat = [];
};
$hxClasses["lime.app.Event_lime_ui_Touch_Void"] = lime_app_Event_$lime_$ui_$Touch_$Void;
lime_app_Event_$lime_$ui_$Touch_$Void.__name__ = true;
lime_app_Event_$lime_$ui_$Touch_$Void.prototype = {
	add: function(listener,once,priority) {
		if(priority == null) priority = 0;
		if(once == null) once = false;
		var _g1 = 0;
		var _g = this.priorities.length;
		while(_g1 < _g) {
			var i = _g1++;
			if(priority > this.priorities[i]) {
				this.listeners.splice(i,0,listener);
				this.priorities.splice(i,0,priority);
				this.repeat.splice(i,0,!once);
				return;
			}
		}
		this.listeners.push(listener);
		this.priorities.push(priority);
		this.repeat.push(!once);
	}
	,remove: function(listener) {
		var i = this.listeners.length;
		while(--i >= 0) if(Reflect.compareMethods(this.listeners[i],listener)) {
			this.listeners.splice(i,1);
			this.priorities.splice(i,1);
			this.repeat.splice(i,1);
		}
	}
	,dispatch: function(a) {
		var listeners = this.listeners;
		var repeat = this.repeat;
		var i = 0;
		while(i < listeners.length) {
			listeners[i](a);
			if(!repeat[i]) this.remove(listeners[i]); else i++;
		}
	}
	,__class__: lime_app_Event_$lime_$ui_$Touch_$Void
};
var lime_app_Preloader = function() {
	this.total = 0;
	this.loaded = 0;
	this.onProgress = new lime_app_Event_$Int_$Int_$Void();
	this.onComplete = new lime_app_Event_$Void_$Void();
	this.onProgress.add($bind(this,this.update));
};
$hxClasses["lime.app.Preloader"] = lime_app_Preloader;
lime_app_Preloader.__name__ = true;
lime_app_Preloader.prototype = {
	create: function(config) {
	}
	,load: function(urls,types) {
		var url = null;
		var _g1 = 0;
		var _g = urls.length;
		while(_g1 < _g) {
			var i = _g1++;
			url = urls[i];
			var _g2 = types[i];
			switch(_g2) {
			case "IMAGE":
				if(!lime_app_Preloader.images.exists(url)) {
					var image = new Image();
					lime_app_Preloader.images.set(url,image);
					image.onload = $bind(this,this.image_onLoad);
					image.src = url;
					this.total++;
				}
				break;
			case "BINARY":
				if(!lime_app_Preloader.loaders.exists(url)) {
					var loader = new lime_net_URLLoader();
					loader.set_dataFormat(lime_net_URLLoaderDataFormat.BINARY);
					lime_app_Preloader.loaders.set(url,loader);
					this.total++;
				}
				break;
			case "TEXT":
				if(!lime_app_Preloader.loaders.exists(url)) {
					var loader1 = new lime_net_URLLoader();
					lime_app_Preloader.loaders.set(url,loader1);
					this.total++;
				}
				break;
			case "FONT":
				this.total++;
				this.loadFont(url);
				break;
			default:
			}
		}
		var $it0 = lime_app_Preloader.loaders.keys();
		while( $it0.hasNext() ) {
			var url1 = $it0.next();
			var loader2 = lime_app_Preloader.loaders.get(url1);
			loader2.onComplete.add($bind(this,this.loader_onComplete));
			loader2.load(new lime_net_URLRequest(url1));
		}
		if(this.total == 0) this.start();
	}
	,loadFont: function(font) {
		var _g = this;
		if(window.document.fonts && ($_=window.document.fonts,$bind($_,$_.load))) window.document.fonts.load("1em '" + font + "'").then(function(_) {
			_g.loaded++;
			_g.onProgress.dispatch(_g.loaded,_g.total);
			if(_g.loaded == _g.total) _g.start();
		}); else {
			var node = window.document.createElement("span");
			node.innerHTML = "giItT1WQy@!-/#";
			var style = node.style;
			style.position = "absolute";
			style.left = "-10000px";
			style.top = "-10000px";
			style.fontSize = "300px";
			style.fontFamily = "sans-serif";
			style.fontVariant = "normal";
			style.fontStyle = "normal";
			style.fontWeight = "normal";
			style.letterSpacing = "0";
			window.document.body.appendChild(node);
			var width = node.offsetWidth;
			style.fontFamily = "'" + font + "', sans-serif";
			var interval = null;
			var found = false;
			var checkFont = function() {
				if(node.offsetWidth != width) {
					if(!found) {
						found = true;
						return false;
					}
					_g.loaded++;
					if(interval != null) window.clearInterval(interval);
					node.parentNode.removeChild(node);
					node = null;
					_g.onProgress.dispatch(_g.loaded,_g.total);
					if(_g.loaded == _g.total) _g.start();
					return true;
				}
				return false;
			};
			if(!checkFont()) interval = window.setInterval(checkFont,50);
		}
	}
	,start: function() {
		this.complete = true;
		this.onComplete.dispatch();
	}
	,update: function(loaded,total) {
	}
	,image_onLoad: function(_) {
		this.loaded++;
		this.onProgress.dispatch(this.loaded,this.total);
		if(this.loaded == this.total) this.start();
	}
	,loader_onComplete: function(loader) {
		this.loaded++;
		this.onProgress.dispatch(this.loaded,this.total);
		if(this.loaded == this.total) this.start();
	}
	,__class__: lime_app_Preloader
};
var lime_audio_ALAudioContext = function() { };
$hxClasses["lime.audio.ALAudioContext"] = lime_audio_ALAudioContext;
lime_audio_ALAudioContext.__name__ = true;
var lime_audio_ALCAudioContext = function() { };
$hxClasses["lime.audio.ALCAudioContext"] = lime_audio_ALCAudioContext;
lime_audio_ALCAudioContext.__name__ = true;
var lime_audio_AudioBuffer = function() { };
$hxClasses["lime.audio.AudioBuffer"] = lime_audio_AudioBuffer;
lime_audio_AudioBuffer.__name__ = true;
var lime_audio_AudioContext = $hxClasses["lime.audio.AudioContext"] = { __ename__ : true, __constructs__ : ["OPENAL","HTML5","WEB","FLASH","CUSTOM"] };
lime_audio_AudioContext.OPENAL = function(alc,al) { var $x = ["OPENAL",0,alc,al]; $x.__enum__ = lime_audio_AudioContext; $x.toString = $estr; return $x; };
lime_audio_AudioContext.HTML5 = function(context) { var $x = ["HTML5",1,context]; $x.__enum__ = lime_audio_AudioContext; $x.toString = $estr; return $x; };
lime_audio_AudioContext.WEB = function(context) { var $x = ["WEB",2,context]; $x.__enum__ = lime_audio_AudioContext; $x.toString = $estr; return $x; };
lime_audio_AudioContext.FLASH = function(context) { var $x = ["FLASH",3,context]; $x.__enum__ = lime_audio_AudioContext; $x.toString = $estr; return $x; };
lime_audio_AudioContext.CUSTOM = function(data) { var $x = ["CUSTOM",4,data]; $x.__enum__ = lime_audio_AudioContext; $x.toString = $estr; return $x; };
var lime_audio_AudioManager = function() { };
$hxClasses["lime.audio.AudioManager"] = lime_audio_AudioManager;
lime_audio_AudioManager.__name__ = true;
lime_audio_AudioManager.context = null;
lime_audio_AudioManager.init = function(context) {
	if(lime_audio_AudioManager.context == null) {
		if(context == null) try {
			window.AudioContext = window.AudioContext || window.webkitAudioContext;;
			lime_audio_AudioManager.context = lime_audio_AudioContext.WEB(new AudioContext ());
		} catch( e ) {
			if (e instanceof js__$Boot_HaxeError) e = e.val;
			lime_audio_AudioManager.context = lime_audio_AudioContext.HTML5(new lime_audio_HTML5AudioContext());
		} else lime_audio_AudioManager.context = context;
	}
};
var lime_audio_FlashAudioContext = function() { };
$hxClasses["lime.audio.FlashAudioContext"] = lime_audio_FlashAudioContext;
lime_audio_FlashAudioContext.__name__ = true;
var lime_audio_HTML5AudioContext = function() {
};
$hxClasses["lime.audio.HTML5AudioContext"] = lime_audio_HTML5AudioContext;
lime_audio_HTML5AudioContext.__name__ = true;
lime_audio_HTML5AudioContext.prototype = {
	__class__: lime_audio_HTML5AudioContext
};
var lime_graphics_ConsoleRenderContext = function() { };
$hxClasses["lime.graphics.ConsoleRenderContext"] = lime_graphics_ConsoleRenderContext;
lime_graphics_ConsoleRenderContext.__name__ = true;
lime_graphics_ConsoleRenderContext.prototype = {
	__class__: lime_graphics_ConsoleRenderContext
};
var lime_graphics_FlashRenderContext = function() { };
$hxClasses["lime.graphics.FlashRenderContext"] = lime_graphics_FlashRenderContext;
lime_graphics_FlashRenderContext.__name__ = true;
var lime_graphics_Image = function(buffer,offsetX,offsetY,width,height,color,type) {
	if(height == null) height = -1;
	if(width == null) width = -1;
	if(offsetY == null) offsetY = 0;
	if(offsetX == null) offsetX = 0;
	this.offsetX = offsetX;
	this.offsetY = offsetY;
	this.width = width;
	this.height = height;
	if(type == null) {
		if(lime_app_Application.current != null && lime_app_Application.current.renderers[0] != null) {
			var _g = lime_app_Application.current.renderers[0].context;
			switch(_g[1]) {
			case 2:case 1:
				this.type = lime_graphics_ImageType.CANVAS;
				break;
			case 3:
				this.type = lime_graphics_ImageType.FLASH;
				break;
			default:
				this.type = lime_graphics_ImageType.DATA;
			}
		} else this.type = lime_graphics_ImageType.DATA;
	} else this.type = type;
	if(buffer == null) {
		if(width > 0 && height > 0) {
			var _g1 = this.type;
			switch(_g1[1]) {
			case 0:
				this.buffer = new lime_graphics_ImageBuffer(null,width,height);
				lime_graphics_utils_ImageCanvasUtil.createCanvas(this,width,height);
				if(color != null) this.fillRect(new lime_math_Rectangle(0,0,width,height),color);
				break;
			case 1:
				this.buffer = new lime_graphics_ImageBuffer((function($this) {
					var $r;
					var elements = width * height * 4;
					var this1;
					if(elements != null) this1 = new Uint8Array(elements); else this1 = null;
					$r = this1;
					return $r;
				}(this)),width,height);
				if(color != null) this.fillRect(new lime_math_Rectangle(0,0,width,height),color);
				break;
			case 2:
				break;
			default:
			}
		}
	} else this.__fromImageBuffer(buffer);
};
$hxClasses["lime.graphics.Image"] = lime_graphics_Image;
lime_graphics_Image.__name__ = true;
lime_graphics_Image.__base64Encoder = null;
lime_graphics_Image.fromBytes = function(bytes,onload) {
	if(bytes == null) return null;
	var image = new lime_graphics_Image();
	image.__fromBytes(bytes,onload);
	return image;
};
lime_graphics_Image.fromCanvas = function(canvas) {
	if(canvas == null) return null;
	var buffer = new lime_graphics_ImageBuffer(null,canvas.width,canvas.height);
	buffer.set_src(canvas);
	return new lime_graphics_Image(buffer);
};
lime_graphics_Image.fromImageElement = function(image) {
	if(image == null) return null;
	var buffer = new lime_graphics_ImageBuffer(null,image.width,image.height);
	buffer.set_src(image);
	return new lime_graphics_Image(buffer);
};
lime_graphics_Image.__base64Encode = function(bytes) {
	var extension;
	var _g = bytes.length % 3;
	switch(_g) {
	case 1:
		extension = "==";
		break;
	case 2:
		extension = "=";
		break;
	default:
		extension = "";
	}
	if(lime_graphics_Image.__base64Encoder == null) lime_graphics_Image.__base64Encoder = new haxe_crypto_BaseCode(haxe_io_Bytes.ofString(lime_graphics_Image.__base64Chars));
	return lime_graphics_Image.__base64Encoder.encodeBytes(haxe_io_Bytes.ofData(bytes.byteView)).toString() + extension;
};
lime_graphics_Image.__isJPG = function(bytes) {
	bytes.position = 0;
	return bytes.readUnsignedByte() == 255 && bytes.readUnsignedByte() == 216;
};
lime_graphics_Image.__isPNG = function(bytes) {
	bytes.position = 0;
	return bytes.readUnsignedByte() == 137 && bytes.readUnsignedByte() == 80 && bytes.readUnsignedByte() == 78 && bytes.readUnsignedByte() == 71 && bytes.readUnsignedByte() == 13 && bytes.readUnsignedByte() == 10 && bytes.readUnsignedByte() == 26 && bytes.readUnsignedByte() == 10;
};
lime_graphics_Image.__isGIF = function(bytes) {
	bytes.position = 0;
	if(bytes.readUnsignedByte() == 71 && bytes.readUnsignedByte() == 73 && bytes.readUnsignedByte() == 70 && bytes.readUnsignedByte() == 56) {
		var b = bytes.readUnsignedByte();
		return (b == 55 || b == 57) && bytes.readUnsignedByte() == 97;
	}
	return false;
};
lime_graphics_Image.prototype = {
	clone: function() {
		if(this.buffer != null) {
			if(this.type == lime_graphics_ImageType.CANVAS && this.buffer.__srcImage == null) {
				lime_graphics_utils_ImageCanvasUtil.convertToCanvas(this);
				lime_graphics_utils_ImageCanvasUtil.sync(this,true);
			}
			var image = new lime_graphics_Image(this.buffer.clone(),this.offsetX,this.offsetY,this.width,this.height,null,this.type);
			image.dirty = this.dirty;
			return image;
		} else return new lime_graphics_Image(null,this.offsetX,this.offsetY,this.width,this.height,null,this.type);
	}
	,colorTransform: function(rect,colorMatrix) {
		rect = this.__clipRect(rect);
		if(this.buffer == null || rect == null) return;
		var _g = this.type;
		switch(_g[1]) {
		case 0:
			lime_graphics_utils_ImageCanvasUtil.colorTransform(this,rect,colorMatrix);
			break;
		case 1:
			lime_graphics_utils_ImageCanvasUtil.convertToData(this);
			lime_graphics_utils_ImageDataUtil.colorTransform(this,rect,colorMatrix);
			break;
		case 2:
			rect.offset(this.offsetX,this.offsetY);
			this.buffer.__srcBitmapData.colorTransform(rect.__toFlashRectangle(),lime_math__$ColorMatrix_ColorMatrix_$Impl_$.__toFlashColorTransform(colorMatrix));
			break;
		default:
		}
	}
	,fillRect: function(rect,color,format) {
		rect = this.__clipRect(rect);
		if(this.buffer == null || rect == null) return;
		var _g = this.type;
		switch(_g[1]) {
		case 0:
			lime_graphics_utils_ImageCanvasUtil.fillRect(this,rect,color,format);
			break;
		case 1:
			lime_graphics_utils_ImageCanvasUtil.convertToData(this);
			if(this.buffer.data.length == 0) return;
			lime_graphics_utils_ImageDataUtil.fillRect(this,rect,color,format);
			break;
		case 2:
			rect.offset(this.offsetX,this.offsetY);
			var argb;
			if(format != null) switch(format) {
			case 1:
				argb = color;
				break;
			case 2:
				{
					var bgra = color;
					var argb1 = 0;
					argb1 = (bgra & 255 & 255) << 24 | (bgra >> 8 & 255 & 255) << 16 | (bgra >> 16 & 255 & 255) << 8 | bgra >> 24 & 255 & 255;
					argb = argb1;
				}
				break;
			default:
				{
					var rgba = color;
					var argb2 = 0;
					argb2 = (rgba & 255 & 255) << 24 | (rgba >> 24 & 255 & 255) << 16 | (rgba >> 16 & 255 & 255) << 8 | rgba >> 8 & 255 & 255;
					argb = argb2;
				}
			} else {
				var rgba1 = color;
				var argb3 = 0;
				argb3 = (rgba1 & 255 & 255) << 24 | (rgba1 >> 24 & 255 & 255) << 16 | (rgba1 >> 16 & 255 & 255) << 8 | rgba1 >> 8 & 255 & 255;
				argb = argb3;
			}
			this.buffer.__srcBitmapData.fillRect(rect.__toFlashRectangle(),argb);
			break;
		default:
		}
	}
	,getPixels: function(rect,format) {
		if(this.buffer == null) return null;
		var _g = this.type;
		switch(_g[1]) {
		case 0:
			return lime_graphics_utils_ImageCanvasUtil.getPixels(this,rect,format);
		case 1:
			lime_graphics_utils_ImageCanvasUtil.convertToData(this);
			return lime_graphics_utils_ImageDataUtil.getPixels(this,rect,format);
		case 2:
			rect.offset(this.offsetX,this.offsetY);
			var byteArray = this.buffer.__srcBitmapData.getPixels(rect.__toFlashRectangle());
			if(format != null) switch(format) {
			case 1:
				break;
			case 2:
				var color;
				var length = byteArray.length / 4 | 0;
				var _g1 = 0;
				while(_g1 < length) {
					var i = _g1++;
					{
						var argb = byteArray.readUnsignedInt();
						var bgra = 0;
						bgra = (argb & 255 & 255) << 24 | (argb >> 8 & 255 & 255) << 16 | (argb >> 16 & 255 & 255) << 8 | argb >> 24 & 255 & 255;
						color = bgra;
					}
					byteArray.position -= 4;
					byteArray.writeUnsignedInt(color);
				}
				byteArray.position = 0;
				break;
			default:
				var color1;
				var length1 = byteArray.length / 4 | 0;
				var _g11 = 0;
				while(_g11 < length1) {
					var i1 = _g11++;
					{
						var argb1 = byteArray.readUnsignedInt();
						var rgba = 0;
						rgba = (argb1 >> 16 & 255 & 255) << 24 | (argb1 >> 8 & 255 & 255) << 16 | (argb1 & 255 & 255) << 8 | argb1 >> 24 & 255 & 255;
						color1 = rgba;
					}
					byteArray.position -= 4;
					byteArray.writeUnsignedInt((function($this) {
						var $r;
						var bgra1 = 0;
						bgra1 = (color1 >> 8 & 255 & 255) << 24 | (color1 >> 16 & 255 & 255) << 16 | (color1 >> 24 & 255 & 255) << 8 | color1 & 255 & 255;
						$r = bgra1;
						return $r;
					}(this)));
				}
				byteArray.position = 0;
			} else {
				var color2;
				var length2 = byteArray.length / 4 | 0;
				var _g12 = 0;
				while(_g12 < length2) {
					var i2 = _g12++;
					{
						var argb2 = byteArray.readUnsignedInt();
						var rgba1 = 0;
						rgba1 = (argb2 >> 16 & 255 & 255) << 24 | (argb2 >> 8 & 255 & 255) << 16 | (argb2 & 255 & 255) << 8 | argb2 >> 24 & 255 & 255;
						color2 = rgba1;
					}
					byteArray.position -= 4;
					byteArray.writeUnsignedInt((function($this) {
						var $r;
						var bgra2 = 0;
						bgra2 = (color2 >> 8 & 255 & 255) << 24 | (color2 >> 16 & 255 & 255) << 16 | (color2 >> 24 & 255 & 255) << 8 | color2 & 255 & 255;
						$r = bgra2;
						return $r;
					}(this)));
				}
				byteArray.position = 0;
			}
			return byteArray;
		default:
			return null;
		}
	}
	,resize: function(newWidth,newHeight) {
		var _g = this.type;
		switch(_g[1]) {
		case 0:
			lime_graphics_utils_ImageCanvasUtil.resize(this,newWidth,newHeight);
			break;
		case 1:
			lime_graphics_utils_ImageDataUtil.resize(this,newWidth,newHeight);
			break;
		case 2:
			break;
		default:
		}
		this.buffer.width = newWidth;
		this.buffer.height = newHeight;
		this.offsetX = 0;
		this.offsetY = 0;
		this.width = newWidth;
		this.height = newHeight;
	}
	,__clipRect: function(r) {
		if(r == null) return null;
		if(r.x < 0) {
			r.width -= -r.x;
			r.x = 0;
			if(r.x + r.width <= 0) return null;
		}
		if(r.y < 0) {
			r.height -= -r.y;
			r.y = 0;
			if(r.y + r.height <= 0) return null;
		}
		if(r.x + r.width >= this.width) {
			r.width -= r.x + r.width - this.width;
			if(r.width <= 0) return null;
		}
		if(r.y + r.height >= this.height) {
			r.height -= r.y + r.height - this.height;
			if(r.height <= 0) return null;
		}
		return r;
	}
	,__fromBase64: function(base64,type,onload) {
		var _g = this;
		var image = new Image();
		var image_onLoaded = function(event) {
			_g.buffer = new lime_graphics_ImageBuffer(null,image.width,image.height);
			_g.buffer.__srcImage = image;
			_g.offsetX = 0;
			_g.offsetY = 0;
			_g.width = _g.buffer.width;
			_g.height = _g.buffer.height;
			if(onload != null) onload(_g);
		};
		image.addEventListener("load",image_onLoaded,false);
		image.src = "data:" + type + ";base64," + base64;
	}
	,__fromBytes: function(bytes,onload) {
		var type = "";
		if(lime_graphics_Image.__isPNG(bytes)) type = "image/png"; else if(lime_graphics_Image.__isJPG(bytes)) type = "image/jpeg"; else if(lime_graphics_Image.__isGIF(bytes)) type = "image/gif"; else throw new js__$Boot_HaxeError("Image tried to read a PNG/JPG ByteArray, but found an invalid header.");
		this.__fromBase64(lime_graphics_Image.__base64Encode(bytes),type,onload);
	}
	,__fromImageBuffer: function(buffer) {
		this.buffer = buffer;
		if(buffer != null) {
			if(this.width == -1) this.width = buffer.width;
			if(this.height == -1) this.height = buffer.height;
		}
	}
	,get_data: function() {
		if(this.buffer.data == null && this.buffer.width > 0 && this.buffer.height > 0) {
			lime_graphics_utils_ImageCanvasUtil.convertToCanvas(this);
			lime_graphics_utils_ImageCanvasUtil.sync(this,false);
			lime_graphics_utils_ImageCanvasUtil.createImageData(this);
		}
		return this.buffer.data;
	}
	,get_format: function() {
		return this.buffer.format;
	}
	,set_format: function(value) {
		if(this.buffer.format != value) {
			var _g = this.type;
			switch(_g[1]) {
			case 1:
				lime_graphics_utils_ImageDataUtil.setFormat(this,value);
				break;
			default:
			}
		}
		return this.buffer.format = value;
	}
	,get_powerOfTwo: function() {
		return this.buffer.width != 0 && (this.buffer.width & ~this.buffer.width + 1) == this.buffer.width && (this.buffer.height != 0 && (this.buffer.height & ~this.buffer.height + 1) == this.buffer.height);
	}
	,get_premultiplied: function() {
		return this.buffer.premultiplied;
	}
	,set_premultiplied: function(value) {
		if(value && !this.buffer.premultiplied) {
			var _g = this.type;
			switch(_g[1]) {
			case 1:
				lime_graphics_utils_ImageCanvasUtil.convertToData(this);
				lime_graphics_utils_ImageDataUtil.multiplyAlpha(this);
				break;
			default:
			}
		} else if(!value && this.buffer.premultiplied) {
			var _g1 = this.type;
			switch(_g1[1]) {
			case 1:
				lime_graphics_utils_ImageCanvasUtil.convertToData(this);
				lime_graphics_utils_ImageDataUtil.unmultiplyAlpha(this);
				break;
			default:
			}
		}
		return value;
	}
	,get_rect: function() {
		return new lime_math_Rectangle(0,0,this.width,this.height);
	}
	,get_src: function() {
		if(this.buffer.__srcCanvas == null) lime_graphics_utils_ImageCanvasUtil.convertToCanvas(this);
		return this.buffer.get_src();
	}
	,get_transparent: function() {
		if(this.buffer == null) return false;
		return this.buffer.transparent;
	}
	,set_transparent: function(value) {
		if(this.buffer == null) return false;
		return this.buffer.transparent = value;
	}
	,__class__: lime_graphics_Image
	,__properties__: {get_rect:"get_rect",set_transparent:"set_transparent",get_transparent:"get_transparent",get_src:"get_src",set_premultiplied:"set_premultiplied",get_premultiplied:"get_premultiplied",get_powerOfTwo:"get_powerOfTwo",set_format:"set_format",get_format:"get_format",get_data:"get_data"}
};
var lime_graphics_ImageBuffer = function(data,width,height,bitsPerPixel,format) {
	if(bitsPerPixel == null) bitsPerPixel = 32;
	if(height == null) height = 0;
	if(width == null) width = 0;
	this.data = data;
	this.width = width;
	this.height = height;
	this.bitsPerPixel = bitsPerPixel;
	if(format == null) this.format = 0; else this.format = format;
	this.transparent = true;
};
$hxClasses["lime.graphics.ImageBuffer"] = lime_graphics_ImageBuffer;
lime_graphics_ImageBuffer.__name__ = true;
lime_graphics_ImageBuffer.prototype = {
	clone: function() {
		var buffer = new lime_graphics_ImageBuffer(this.data,this.width,this.height,this.bitsPerPixel);
		if(this.data != null) {
			var elements = this.data.byteLength;
			var this1;
			if(elements != null) this1 = new Uint8Array(elements); else this1 = null;
			buffer.data = this1;
			var copy;
			var view = this.data;
			var this2;
			if(view != null) this2 = new Uint8Array(view); else this2 = null;
			copy = this2;
			buffer.data.set(copy);
		} else if(this.__srcImageData != null) {
			buffer.__srcCanvas = window.document.createElement("canvas");
			buffer.__srcContext = buffer.__srcCanvas.getContext("2d");
			buffer.__srcCanvas.width = this.__srcImageData.width;
			buffer.__srcCanvas.height = this.__srcImageData.height;
			buffer.__srcImageData = buffer.__srcContext.createImageData(this.__srcImageData.width,this.__srcImageData.height);
			var copy1 = new Uint8ClampedArray(this.__srcImageData.data);
			buffer.__srcImageData.data.set(copy1);
		} else if(this.__srcCanvas != null) {
			buffer.__srcCanvas = window.document.createElement("canvas");
			buffer.__srcContext = buffer.__srcCanvas.getContext("2d");
			buffer.__srcCanvas.width = this.__srcCanvas.width;
			buffer.__srcCanvas.height = this.__srcCanvas.height;
			buffer.__srcContext.drawImage(this.__srcCanvas,0,0);
		} else buffer.__srcImage = this.__srcImage;
		buffer.bitsPerPixel = this.bitsPerPixel;
		buffer.format = this.format;
		buffer.premultiplied = this.premultiplied;
		buffer.transparent = this.transparent;
		return buffer;
	}
	,get_src: function() {
		if(this.__srcImage != null) return this.__srcImage;
		return this.__srcCanvas;
	}
	,set_src: function(value) {
		if(js_Boot.__instanceof(value,Image)) this.__srcImage = value; else if(js_Boot.__instanceof(value,HTMLCanvasElement)) {
			this.__srcCanvas = value;
			this.__srcContext = this.__srcCanvas.getContext("2d");
		}
		return value;
	}
	,get_stride: function() {
		return this.width * 4;
	}
	,__class__: lime_graphics_ImageBuffer
	,__properties__: {get_stride:"get_stride",set_src:"set_src",get_src:"get_src"}
};
var lime_graphics_ImageType = $hxClasses["lime.graphics.ImageType"] = { __ename__ : true, __constructs__ : ["CANVAS","DATA","FLASH","CUSTOM"] };
lime_graphics_ImageType.CANVAS = ["CANVAS",0];
lime_graphics_ImageType.CANVAS.toString = $estr;
lime_graphics_ImageType.CANVAS.__enum__ = lime_graphics_ImageType;
lime_graphics_ImageType.DATA = ["DATA",1];
lime_graphics_ImageType.DATA.toString = $estr;
lime_graphics_ImageType.DATA.__enum__ = lime_graphics_ImageType;
lime_graphics_ImageType.FLASH = ["FLASH",2];
lime_graphics_ImageType.FLASH.toString = $estr;
lime_graphics_ImageType.FLASH.__enum__ = lime_graphics_ImageType;
lime_graphics_ImageType.CUSTOM = ["CUSTOM",3];
lime_graphics_ImageType.CUSTOM.toString = $estr;
lime_graphics_ImageType.CUSTOM.__enum__ = lime_graphics_ImageType;
var lime_graphics_RenderContext = $hxClasses["lime.graphics.RenderContext"] = { __ename__ : true, __constructs__ : ["OPENGL","CANVAS","DOM","FLASH","CAIRO","CONSOLE","CUSTOM","NONE"] };
lime_graphics_RenderContext.OPENGL = function(gl) { var $x = ["OPENGL",0,gl]; $x.__enum__ = lime_graphics_RenderContext; $x.toString = $estr; return $x; };
lime_graphics_RenderContext.CANVAS = function(context) { var $x = ["CANVAS",1,context]; $x.__enum__ = lime_graphics_RenderContext; $x.toString = $estr; return $x; };
lime_graphics_RenderContext.DOM = function(element) { var $x = ["DOM",2,element]; $x.__enum__ = lime_graphics_RenderContext; $x.toString = $estr; return $x; };
lime_graphics_RenderContext.FLASH = function(stage) { var $x = ["FLASH",3,stage]; $x.__enum__ = lime_graphics_RenderContext; $x.toString = $estr; return $x; };
lime_graphics_RenderContext.CAIRO = function(cairo) { var $x = ["CAIRO",4,cairo]; $x.__enum__ = lime_graphics_RenderContext; $x.toString = $estr; return $x; };
lime_graphics_RenderContext.CONSOLE = function(context) { var $x = ["CONSOLE",5,context]; $x.__enum__ = lime_graphics_RenderContext; $x.toString = $estr; return $x; };
lime_graphics_RenderContext.CUSTOM = function(data) { var $x = ["CUSTOM",6,data]; $x.__enum__ = lime_graphics_RenderContext; $x.toString = $estr; return $x; };
lime_graphics_RenderContext.NONE = ["NONE",7];
lime_graphics_RenderContext.NONE.toString = $estr;
lime_graphics_RenderContext.NONE.__enum__ = lime_graphics_RenderContext;
var lime_graphics_Renderer = function(window) {
	this.onRender = new lime_app_Event_$Void_$Void();
	this.onContextRestored = new lime_app_Event_$lime_$graphics_$RenderContext_$Void();
	this.onContextLost = new lime_app_Event_$Void_$Void();
	this.window = window;
	this.backend = new lime__$backend_html5_HTML5Renderer(this);
	this.window.renderer = this;
};
$hxClasses["lime.graphics.Renderer"] = lime_graphics_Renderer;
lime_graphics_Renderer.__name__ = true;
lime_graphics_Renderer.prototype = {
	create: function() {
		this.backend.create();
	}
	,flip: function() {
		this.backend.flip();
	}
	,__class__: lime_graphics_Renderer
};
var lime_graphics_RendererType = $hxClasses["lime.graphics.RendererType"] = { __ename__ : true, __constructs__ : ["OPENGL","CANVAS","DOM","FLASH","CAIRO","CONSOLE","CUSTOM"] };
lime_graphics_RendererType.OPENGL = ["OPENGL",0];
lime_graphics_RendererType.OPENGL.toString = $estr;
lime_graphics_RendererType.OPENGL.__enum__ = lime_graphics_RendererType;
lime_graphics_RendererType.CANVAS = ["CANVAS",1];
lime_graphics_RendererType.CANVAS.toString = $estr;
lime_graphics_RendererType.CANVAS.__enum__ = lime_graphics_RendererType;
lime_graphics_RendererType.DOM = ["DOM",2];
lime_graphics_RendererType.DOM.toString = $estr;
lime_graphics_RendererType.DOM.__enum__ = lime_graphics_RendererType;
lime_graphics_RendererType.FLASH = ["FLASH",3];
lime_graphics_RendererType.FLASH.toString = $estr;
lime_graphics_RendererType.FLASH.__enum__ = lime_graphics_RendererType;
lime_graphics_RendererType.CAIRO = ["CAIRO",4];
lime_graphics_RendererType.CAIRO.toString = $estr;
lime_graphics_RendererType.CAIRO.__enum__ = lime_graphics_RendererType;
lime_graphics_RendererType.CONSOLE = ["CONSOLE",5];
lime_graphics_RendererType.CONSOLE.toString = $estr;
lime_graphics_RendererType.CONSOLE.__enum__ = lime_graphics_RendererType;
lime_graphics_RendererType.CUSTOM = ["CUSTOM",6];
lime_graphics_RendererType.CUSTOM.toString = $estr;
lime_graphics_RendererType.CUSTOM.__enum__ = lime_graphics_RendererType;
var lime_graphics_cairo_Cairo = function() { };
$hxClasses["lime.graphics.cairo.Cairo"] = lime_graphics_cairo_Cairo;
lime_graphics_cairo_Cairo.__name__ = true;
lime_graphics_cairo_Cairo.prototype = {
	arc: function(xc,yc,radius,angle1,angle2) {
	}
	,clip: function() {
	}
	,curveTo: function(x1,y1,x2,y2,x3,y3) {
	}
	,fill: function() {
	}
	,identityMatrix: function() {
	}
	,lineTo: function(x,y) {
	}
	,moveTo: function(x,y) {
	}
	,newPath: function() {
	}
	,paint: function() {
	}
	,paintWithAlpha: function(alpha) {
	}
	,popGroupToSource: function() {
	}
	,pushGroup: function() {
	}
	,rectangle: function(x,y,width,height) {
	}
	,restore: function() {
	}
	,save: function() {
	}
	,setSourceRGB: function(r,g,b) {
	}
	,get_currentPoint: function() {
		return null;
	}
	,get_hasCurrentPoint: function() {
		return false;
	}
	,set_matrix: function(value) {
		return value;
	}
	,set_source: function(value) {
		return value;
	}
	,__class__: lime_graphics_cairo_Cairo
	,__properties__: {set_source:"set_source",set_matrix:"set_matrix",get_hasCurrentPoint:"get_hasCurrentPoint",get_currentPoint:"get_currentPoint"}
};
var lime_graphics_cairo__$CairoImageSurface_CairoImageSurface_$Impl_$ = {};
$hxClasses["lime.graphics.cairo._CairoImageSurface.CairoImageSurface_Impl_"] = lime_graphics_cairo__$CairoImageSurface_CairoImageSurface_$Impl_$;
lime_graphics_cairo__$CairoImageSurface_CairoImageSurface_$Impl_$.__name__ = true;
lime_graphics_cairo__$CairoImageSurface_CairoImageSurface_$Impl_$.fromImage = function(image) {
	return null;
};
var lime_graphics_cairo__$CairoPattern_CairoPattern_$Impl_$ = {};
$hxClasses["lime.graphics.cairo._CairoPattern.CairoPattern_Impl_"] = lime_graphics_cairo__$CairoPattern_CairoPattern_$Impl_$;
lime_graphics_cairo__$CairoPattern_CairoPattern_$Impl_$.__name__ = true;
lime_graphics_cairo__$CairoPattern_CairoPattern_$Impl_$.__properties__ = {set_filter:"set_filter"}
lime_graphics_cairo__$CairoPattern_CairoPattern_$Impl_$.createForSurface = function(surface) {
	return 0;
};
lime_graphics_cairo__$CairoPattern_CairoPattern_$Impl_$.set_filter = function(this1,value) {
	return value;
};
var lime_graphics_cairo__$CairoSurface_CairoSurface_$Impl_$ = {};
$hxClasses["lime.graphics.cairo._CairoSurface.CairoSurface_Impl_"] = lime_graphics_cairo__$CairoSurface_CairoSurface_$Impl_$;
lime_graphics_cairo__$CairoSurface_CairoSurface_$Impl_$.__name__ = true;
lime_graphics_cairo__$CairoSurface_CairoSurface_$Impl_$.flush = function(this1) {
};
var lime_graphics_opengl_GL = function() { };
$hxClasses["lime.graphics.opengl.GL"] = lime_graphics_opengl_GL;
lime_graphics_opengl_GL.__name__ = true;
lime_graphics_opengl_GL.context = null;
var lime_graphics_utils_ImageCanvasUtil = function() { };
$hxClasses["lime.graphics.utils.ImageCanvasUtil"] = lime_graphics_utils_ImageCanvasUtil;
lime_graphics_utils_ImageCanvasUtil.__name__ = true;
lime_graphics_utils_ImageCanvasUtil.colorTransform = function(image,rect,colorMatrix) {
	lime_graphics_utils_ImageCanvasUtil.convertToCanvas(image);
	lime_graphics_utils_ImageCanvasUtil.createImageData(image);
	lime_graphics_utils_ImageDataUtil.colorTransform(image,rect,colorMatrix);
};
lime_graphics_utils_ImageCanvasUtil.convertToCanvas = function(image) {
	var buffer = image.buffer;
	if(buffer.__srcImage != null) {
		if(buffer.__srcCanvas == null) {
			lime_graphics_utils_ImageCanvasUtil.createCanvas(image,buffer.__srcImage.width,buffer.__srcImage.height);
			buffer.__srcContext.drawImage(buffer.__srcImage,0,0);
		}
		buffer.__srcImage = null;
	} else if(buffer.data != null && buffer.__srcCanvas == null) {
		lime_graphics_utils_ImageCanvasUtil.createCanvas(image,buffer.width,buffer.height);
		lime_graphics_utils_ImageCanvasUtil.createImageData(image);
	} else if(buffer.data == null && buffer.__srcImageData != null) buffer.data = buffer.__srcImageData.data;
};
lime_graphics_utils_ImageCanvasUtil.convertToData = function(image) {
	if(image.buffer.data == null) {
		lime_graphics_utils_ImageCanvasUtil.convertToCanvas(image);
		lime_graphics_utils_ImageCanvasUtil.sync(image,false);
		lime_graphics_utils_ImageCanvasUtil.createImageData(image);
		image.buffer.__srcCanvas = null;
		image.buffer.__srcContext = null;
	}
};
lime_graphics_utils_ImageCanvasUtil.createCanvas = function(image,width,height) {
	var buffer = image.buffer;
	if(buffer.__srcCanvas == null) {
		buffer.__srcCanvas = window.document.createElement("canvas");
		buffer.__srcCanvas.width = width;
		buffer.__srcCanvas.height = height;
		if(!image.get_transparent()) {
			if(!image.get_transparent()) buffer.__srcCanvas.setAttribute("moz-opaque","true");
			buffer.__srcContext = buffer.__srcCanvas.getContext ("2d", { alpha: false });
		} else buffer.__srcContext = buffer.__srcCanvas.getContext("2d");
		buffer.__srcContext.mozImageSmoothingEnabled = false;
		buffer.__srcContext.msImageSmoothingEnabled = false;
		buffer.__srcContext.imageSmoothingEnabled = false;
	}
};
lime_graphics_utils_ImageCanvasUtil.createImageData = function(image) {
	var buffer = image.buffer;
	if(buffer.__srcImageData == null) {
		if(buffer.data == null) buffer.__srcImageData = buffer.__srcContext.getImageData(0,0,buffer.width,buffer.height); else {
			buffer.__srcImageData = buffer.__srcContext.createImageData(buffer.width,buffer.height);
			buffer.__srcImageData.data.set(buffer.data);
		}
		var elements = buffer.__srcImageData.data.buffer;
		var this1;
		if(elements != null) this1 = new Uint8Array(elements); else this1 = null;
		buffer.data = this1;
	}
};
lime_graphics_utils_ImageCanvasUtil.fillRect = function(image,rect,color,format) {
	lime_graphics_utils_ImageCanvasUtil.convertToCanvas(image);
	lime_graphics_utils_ImageCanvasUtil.sync(image,true);
	if(rect.x == 0 && rect.y == 0 && rect.width == image.width && rect.height == image.height) {
		if(image.get_transparent() && (color & 255) == 0) {
			image.buffer.__srcCanvas.width = image.buffer.width;
			return;
		}
	}
	var r;
	var g;
	var b;
	var a;
	if(format == 1) {
		r = color >> 16 & 255;
		g = color >> 8 & 255;
		b = color & 255;
		if(image.get_transparent()) a = color >> 24 & 255; else a = 255;
	} else {
		r = color >> 24 & 255;
		g = color >> 16 & 255;
		b = color >> 8 & 255;
		if(image.get_transparent()) a = color & 255; else a = 255;
	}
	image.buffer.__srcContext.fillStyle = "rgba(" + r + ", " + g + ", " + b + ", " + a / 255 + ")";
	image.buffer.__srcContext.fillRect(rect.x + image.offsetX,rect.y + image.offsetY,rect.width + image.offsetX,rect.height + image.offsetY);
};
lime_graphics_utils_ImageCanvasUtil.getPixels = function(image,rect,format) {
	lime_graphics_utils_ImageCanvasUtil.convertToCanvas(image);
	lime_graphics_utils_ImageCanvasUtil.createImageData(image);
	return lime_graphics_utils_ImageDataUtil.getPixels(image,rect,format);
};
lime_graphics_utils_ImageCanvasUtil.resize = function(image,newWidth,newHeight) {
	var buffer = image.buffer;
	if(buffer.__srcCanvas == null) {
		lime_graphics_utils_ImageCanvasUtil.createCanvas(image,newWidth,newHeight);
		buffer.__srcContext.drawImage(buffer.get_src(),0,0,newWidth,newHeight);
	} else {
		lime_graphics_utils_ImageCanvasUtil.sync(image,true);
		var sourceCanvas = buffer.__srcCanvas;
		buffer.__srcCanvas = null;
		lime_graphics_utils_ImageCanvasUtil.createCanvas(image,newWidth,newHeight);
		buffer.__srcContext.drawImage(sourceCanvas,0,0,newWidth,newHeight);
	}
};
lime_graphics_utils_ImageCanvasUtil.sync = function(image,clear) {
	if(image.dirty && image.buffer.__srcImageData != null && image.type != lime_graphics_ImageType.DATA) {
		image.buffer.__srcContext.putImageData(image.buffer.__srcImageData,0,0);
		image.buffer.data = null;
		image.dirty = false;
	}
	if(clear) {
		image.buffer.__srcImageData = null;
		image.buffer.data = null;
	}
};
var lime_graphics_utils_ImageDataUtil = function() { };
$hxClasses["lime.graphics.utils.ImageDataUtil"] = lime_graphics_utils_ImageDataUtil;
lime_graphics_utils_ImageDataUtil.__name__ = true;
lime_graphics_utils_ImageDataUtil.colorTransform = function(image,rect,colorMatrix) {
	var data = image.buffer.data;
	if(data == null) return;
	var format = image.buffer.format;
	var premultiplied = image.buffer.premultiplied;
	var dataView = new lime_graphics_utils__$ImageDataUtil_ImageDataView(image,rect);
	var alphaTable = lime_math__$ColorMatrix_ColorMatrix_$Impl_$.getAlphaTable(colorMatrix);
	var redTable = lime_math__$ColorMatrix_ColorMatrix_$Impl_$.getRedTable(colorMatrix);
	var greenTable = lime_math__$ColorMatrix_ColorMatrix_$Impl_$.getGreenTable(colorMatrix);
	var blueTable = lime_math__$ColorMatrix_ColorMatrix_$Impl_$.getBlueTable(colorMatrix);
	var row;
	var offset;
	var pixel;
	var _g1 = 0;
	var _g = dataView.height;
	while(_g1 < _g) {
		var y = _g1++;
		row = dataView.offset + dataView.stride * y;
		var _g3 = 0;
		var _g2 = dataView.width;
		while(_g3 < _g2) {
			var x = _g3++;
			offset = row + x * 4;
			switch(format) {
			case 2:
				pixel = (data[offset + 2] & 255) << 24 | (data[offset + 1] & 255) << 16 | (data[offset] & 255) << 8 | data[offset + 3] & 255;
				break;
			case 0:
				pixel = (data[offset] & 255) << 24 | (data[offset + 1] & 255) << 16 | (data[offset + 2] & 255) << 8 | data[offset + 3] & 255;
				break;
			case 1:
				pixel = (data[offset + 1] & 255) << 24 | (data[offset + 2] & 255) << 16 | (data[offset + 3] & 255) << 8 | data[offset] & 255;
				break;
			}
			if(premultiplied) {
				if((pixel & 255) != 0 && (pixel & 255) != 255) {
					lime_math_color__$RGBA_RGBA_$Impl_$.unmult = 255.0 / (pixel & 255);
					var r;
					var idx = Math.round((pixel >> 24 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.unmult);
					r = lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[idx];
					var g;
					var idx1 = Math.round((pixel >> 16 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.unmult);
					g = lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[idx1];
					var b;
					var idx2 = Math.round((pixel >> 8 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.unmult);
					b = lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[idx2];
					pixel = (r & 255) << 24 | (g & 255) << 16 | (b & 255) << 8 | pixel & 255 & 255;
				}
			}
			pixel = (redTable[pixel >> 24 & 255] & 255) << 24 | (greenTable[pixel >> 16 & 255] & 255) << 16 | (blueTable[pixel >> 8 & 255] & 255) << 8 | alphaTable[pixel & 255] & 255;
			if(premultiplied) {
				if((pixel & 255) == 0) {
					if(pixel != 0) pixel = 0;
				} else if((pixel & 255) != 255) {
					lime_math_color__$RGBA_RGBA_$Impl_$.a16 = lime_math_color__$RGBA_RGBA_$Impl_$.__alpha16[pixel & 255];
					pixel = ((pixel >> 24 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.a16 >> 16 & 255) << 24 | ((pixel >> 16 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.a16 >> 16 & 255) << 16 | ((pixel >> 8 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.a16 >> 16 & 255) << 8 | pixel & 255 & 255;
				}
			}
			switch(format) {
			case 2:
				data[offset] = pixel >> 8 & 255;
				data[offset + 1] = pixel >> 16 & 255;
				data[offset + 2] = pixel >> 24 & 255;
				data[offset + 3] = pixel & 255;
				break;
			case 0:
				data[offset] = pixel >> 24 & 255;
				data[offset + 1] = pixel >> 16 & 255;
				data[offset + 2] = pixel >> 8 & 255;
				data[offset + 3] = pixel & 255;
				break;
			case 1:
				data[offset] = pixel & 255;
				data[offset + 1] = pixel >> 24 & 255;
				data[offset + 2] = pixel >> 16 & 255;
				data[offset + 3] = pixel >> 8 & 255;
				break;
			}
		}
	}
	image.dirty = true;
};
lime_graphics_utils_ImageDataUtil.fillRect = function(image,rect,color,format) {
	var fillColor;
	switch(format) {
	case 1:
		{
			var argb = color;
			var rgba = 0;
			rgba = (argb >> 16 & 255 & 255) << 24 | (argb >> 8 & 255 & 255) << 16 | (argb & 255 & 255) << 8 | argb >> 24 & 255 & 255;
			fillColor = rgba;
		}
		break;
	case 2:
		{
			var bgra = color;
			var rgba1 = 0;
			rgba1 = (bgra >> 8 & 255 & 255) << 24 | (bgra >> 16 & 255 & 255) << 16 | (bgra >> 24 & 255 & 255) << 8 | bgra & 255 & 255;
			fillColor = rgba1;
		}
		break;
	default:
		fillColor = color;
	}
	if(!image.get_transparent()) {
		fillColor = (fillColor >> 24 & 255 & 255) << 24 | (fillColor >> 16 & 255 & 255) << 16 | (fillColor >> 8 & 255 & 255) << 8 | 255;
		255;
	}
	var data = image.buffer.data;
	if(data == null) return;
	var format1 = image.buffer.format;
	var premultiplied = image.buffer.premultiplied;
	var dataView = new lime_graphics_utils__$ImageDataUtil_ImageDataView(image,rect);
	var row;
	var _g1 = 0;
	var _g = dataView.height;
	while(_g1 < _g) {
		var y = _g1++;
		row = dataView.offset + dataView.stride * y;
		var _g3 = 0;
		var _g2 = dataView.width;
		while(_g3 < _g2) {
			var x = _g3++;
			var offset = row + x * 4;
			if(premultiplied) {
				if((fillColor & 255) == 0) {
					if(fillColor != 0) fillColor = 0;
				} else if((fillColor & 255) != 255) {
					lime_math_color__$RGBA_RGBA_$Impl_$.a16 = lime_math_color__$RGBA_RGBA_$Impl_$.__alpha16[fillColor & 255];
					fillColor = ((fillColor >> 24 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.a16 >> 16 & 255) << 24 | ((fillColor >> 16 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.a16 >> 16 & 255) << 16 | ((fillColor >> 8 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.a16 >> 16 & 255) << 8 | fillColor & 255 & 255;
				}
			}
			switch(format1) {
			case 2:
				data[offset] = fillColor >> 8 & 255;
				data[offset + 1] = fillColor >> 16 & 255;
				data[offset + 2] = fillColor >> 24 & 255;
				data[offset + 3] = fillColor & 255;
				break;
			case 0:
				data[offset] = fillColor >> 24 & 255;
				data[offset + 1] = fillColor >> 16 & 255;
				data[offset + 2] = fillColor >> 8 & 255;
				data[offset + 3] = fillColor & 255;
				break;
			case 1:
				data[offset] = fillColor & 255;
				data[offset + 1] = fillColor >> 24 & 255;
				data[offset + 2] = fillColor >> 16 & 255;
				data[offset + 3] = fillColor >> 8 & 255;
				break;
			}
		}
	}
	image.dirty = true;
};
lime_graphics_utils_ImageDataUtil.getPixels = function(image,rect,format) {
	if(image.buffer.data == null) return null;
	var length = rect.width * rect.height | 0;
	var byteArray = new lime_utils_ByteArray(length * 4);
	byteArray.position = 0;
	var data = image.buffer.data;
	var sourceFormat = image.buffer.format;
	var premultiplied = image.buffer.premultiplied;
	var dataView = new lime_graphics_utils__$ImageDataUtil_ImageDataView(image,rect);
	var position;
	var argb;
	var bgra;
	var pixel;
	var destPosition = 0;
	var _g1 = 0;
	var _g = dataView.height;
	while(_g1 < _g) {
		var y = _g1++;
		position = dataView.offset + dataView.stride * y;
		var _g3 = 0;
		var _g2 = dataView.width;
		while(_g3 < _g2) {
			var x = _g3++;
			switch(sourceFormat) {
			case 2:
				pixel = (data[position + 2] & 255) << 24 | (data[position + 1] & 255) << 16 | (data[position] & 255) << 8 | data[position + 3] & 255;
				break;
			case 0:
				pixel = (data[position] & 255) << 24 | (data[position + 1] & 255) << 16 | (data[position + 2] & 255) << 8 | data[position + 3] & 255;
				break;
			case 1:
				pixel = (data[position + 1] & 255) << 24 | (data[position + 2] & 255) << 16 | (data[position + 3] & 255) << 8 | data[position] & 255;
				break;
			}
			if(premultiplied) {
				if((pixel & 255) != 0 && (pixel & 255) != 255) {
					lime_math_color__$RGBA_RGBA_$Impl_$.unmult = 255.0 / (pixel & 255);
					var r;
					var idx = Math.round((pixel >> 24 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.unmult);
					r = lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[idx];
					var g;
					var idx1 = Math.round((pixel >> 16 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.unmult);
					g = lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[idx1];
					var b;
					var idx2 = Math.round((pixel >> 8 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.unmult);
					b = lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[idx2];
					pixel = (r & 255) << 24 | (g & 255) << 16 | (b & 255) << 8 | pixel & 255 & 255;
				}
			}
			switch(format) {
			case 1:
				{
					var argb1 = 0;
					argb1 = (pixel & 255 & 255) << 24 | (pixel >> 24 & 255 & 255) << 16 | (pixel >> 16 & 255 & 255) << 8 | pixel >> 8 & 255 & 255;
					argb = argb1;
				}
				pixel = argb;
				break;
			case 2:
				{
					var bgra1 = 0;
					bgra1 = (pixel >> 8 & 255 & 255) << 24 | (pixel >> 16 & 255 & 255) << 16 | (pixel >> 24 & 255 & 255) << 8 | pixel & 255 & 255;
					bgra = bgra1;
				}
				pixel = bgra;
				break;
			default:
			}
			byteArray.__set(destPosition++,pixel >> 24 & 255);
			byteArray.__set(destPosition++,pixel >> 16 & 255);
			byteArray.__set(destPosition++,pixel >> 8 & 255);
			byteArray.__set(destPosition++,pixel & 255);
			position += 4;
		}
	}
	byteArray.position = 0;
	return byteArray;
};
lime_graphics_utils_ImageDataUtil.multiplyAlpha = function(image) {
	var data = image.buffer.data;
	if(data == null || !image.buffer.transparent) return;
	var format = image.buffer.format;
	var length = data.length / 4 | 0;
	var pixel;
	var _g = 0;
	while(_g < length) {
		var i = _g++;
		var offset = i * 4;
		switch(format) {
		case 2:
			pixel = (data[offset + 2] & 255) << 24 | (data[offset + 1] & 255) << 16 | (data[offset] & 255) << 8 | data[offset + 3] & 255;
			break;
		case 0:
			pixel = (data[offset] & 255) << 24 | (data[offset + 1] & 255) << 16 | (data[offset + 2] & 255) << 8 | data[offset + 3] & 255;
			break;
		case 1:
			pixel = (data[offset + 1] & 255) << 24 | (data[offset + 2] & 255) << 16 | (data[offset + 3] & 255) << 8 | data[offset] & 255;
			break;
		}
		var offset1 = i * 4;
		if((pixel & 255) == 0) {
			if(pixel != 0) pixel = 0;
		} else if((pixel & 255) != 255) {
			lime_math_color__$RGBA_RGBA_$Impl_$.a16 = lime_math_color__$RGBA_RGBA_$Impl_$.__alpha16[pixel & 255];
			pixel = ((pixel >> 24 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.a16 >> 16 & 255) << 24 | ((pixel >> 16 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.a16 >> 16 & 255) << 16 | ((pixel >> 8 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.a16 >> 16 & 255) << 8 | pixel & 255 & 255;
		}
		switch(format) {
		case 2:
			data[offset1] = pixel >> 8 & 255;
			data[offset1 + 1] = pixel >> 16 & 255;
			data[offset1 + 2] = pixel >> 24 & 255;
			data[offset1 + 3] = pixel & 255;
			break;
		case 0:
			data[offset1] = pixel >> 24 & 255;
			data[offset1 + 1] = pixel >> 16 & 255;
			data[offset1 + 2] = pixel >> 8 & 255;
			data[offset1 + 3] = pixel & 255;
			break;
		case 1:
			data[offset1] = pixel & 255;
			data[offset1 + 1] = pixel >> 24 & 255;
			data[offset1 + 2] = pixel >> 16 & 255;
			data[offset1 + 3] = pixel >> 8 & 255;
			break;
		}
	}
	image.buffer.premultiplied = true;
	image.dirty = true;
};
lime_graphics_utils_ImageDataUtil.resize = function(image,newWidth,newHeight) {
	var buffer = image.buffer;
	if(buffer.width == newWidth && buffer.height == newHeight) return;
	var newBuffer = new lime_graphics_ImageBuffer((function($this) {
		var $r;
		var elements = newWidth * newHeight * 4;
		var this1;
		if(elements != null) this1 = new Uint8Array(elements); else this1 = null;
		$r = this1;
		return $r;
	}(this)),newWidth,newHeight);
	var imageWidth = image.width;
	var imageHeight = image.height;
	var data = image.get_data();
	var newData = newBuffer.data;
	var sourceIndex;
	var sourceIndexX;
	var sourceIndexY;
	var sourceIndexXY;
	var index;
	var sourceX;
	var sourceY;
	var u;
	var v;
	var uRatio;
	var vRatio;
	var uOpposite;
	var vOpposite;
	var _g = 0;
	while(_g < newHeight) {
		var y = _g++;
		var _g1 = 0;
		while(_g1 < newWidth) {
			var x = _g1++;
			u = (x + 0.5) / newWidth * imageWidth - 0.5;
			v = (y + 0.5) / newHeight * imageHeight - 0.5;
			sourceX = u | 0;
			sourceY = v | 0;
			sourceIndex = (sourceY * imageWidth + sourceX) * 4;
			if(sourceX < imageWidth - 1) sourceIndexX = sourceIndex + 4; else sourceIndexX = sourceIndex;
			if(sourceY < imageHeight - 1) sourceIndexY = sourceIndex + imageWidth * 4; else sourceIndexY = sourceIndex;
			if(sourceIndexX != sourceIndex) sourceIndexXY = sourceIndexY + 4; else sourceIndexXY = sourceIndexY;
			index = (y * newWidth + x) * 4;
			uRatio = u - sourceX;
			vRatio = v - sourceY;
			uOpposite = 1 - uRatio;
			vOpposite = 1 - vRatio;
			var val = Std["int"]((_$UInt_UInt_$Impl_$.toFloat(data[sourceIndex]) * uOpposite + _$UInt_UInt_$Impl_$.toFloat(data[sourceIndexX]) * uRatio) * vOpposite + (_$UInt_UInt_$Impl_$.toFloat(data[sourceIndexY]) * uOpposite + _$UInt_UInt_$Impl_$.toFloat(data[sourceIndexXY]) * uRatio) * vRatio);
			newData[index] = val;
			var val1 = Std["int"]((_$UInt_UInt_$Impl_$.toFloat(data[sourceIndex + 1]) * uOpposite + _$UInt_UInt_$Impl_$.toFloat(data[sourceIndexX + 1]) * uRatio) * vOpposite + (_$UInt_UInt_$Impl_$.toFloat(data[sourceIndexY + 1]) * uOpposite + _$UInt_UInt_$Impl_$.toFloat(data[sourceIndexXY + 1]) * uRatio) * vRatio);
			newData[index + 1] = val1;
			var val2 = Std["int"]((_$UInt_UInt_$Impl_$.toFloat(data[sourceIndex + 2]) * uOpposite + _$UInt_UInt_$Impl_$.toFloat(data[sourceIndexX + 2]) * uRatio) * vOpposite + (_$UInt_UInt_$Impl_$.toFloat(data[sourceIndexY + 2]) * uOpposite + _$UInt_UInt_$Impl_$.toFloat(data[sourceIndexXY + 2]) * uRatio) * vRatio);
			newData[index + 2] = val2;
			if(data[sourceIndexX + 3] == 0 || data[sourceIndexY + 3] == 0 || data[sourceIndexXY + 3] == 0) newData[index + 3] = 0; else newData[index + 3] = data[sourceIndex + 3];
		}
	}
	buffer.data = newBuffer.data;
	buffer.width = newWidth;
	buffer.height = newHeight;
};
lime_graphics_utils_ImageDataUtil.setFormat = function(image,format) {
	var data = image.buffer.data;
	if(data == null) return;
	var index;
	var a16;
	var length = data.length / 4 | 0;
	var r1;
	var g1;
	var b1;
	var a1;
	var r2;
	var g2;
	var b2;
	var a2;
	var r;
	var g;
	var b;
	var a;
	var _g = image.get_format();
	switch(_g) {
	case 0:
		r1 = 0;
		g1 = 1;
		b1 = 2;
		a1 = 3;
		break;
	case 1:
		r1 = 1;
		g1 = 2;
		b1 = 3;
		a1 = 0;
		break;
	case 2:
		r1 = 2;
		g1 = 1;
		b1 = 0;
		a1 = 3;
		break;
	}
	switch(format) {
	case 0:
		r2 = 0;
		g2 = 1;
		b2 = 2;
		a2 = 3;
		break;
	case 1:
		r2 = 1;
		g2 = 2;
		b2 = 3;
		a2 = 0;
		break;
	case 2:
		r2 = 2;
		g2 = 1;
		b2 = 0;
		a2 = 3;
		break;
	}
	var _g1 = 0;
	while(_g1 < length) {
		var i = _g1++;
		index = i * 4;
		r = data[index + r1];
		g = data[index + g1];
		b = data[index + b1];
		a = data[index + a1];
		data[index + r2] = r;
		data[index + g2] = g;
		data[index + b2] = b;
		data[index + a2] = a;
	}
	image.buffer.format = format;
	image.dirty = true;
};
lime_graphics_utils_ImageDataUtil.unmultiplyAlpha = function(image) {
	var data = image.buffer.data;
	if(data == null) return;
	var format = image.buffer.format;
	var length = data.length / 4 | 0;
	var pixel;
	var _g = 0;
	while(_g < length) {
		var i = _g++;
		var offset = i * 4;
		switch(format) {
		case 2:
			pixel = (data[offset + 2] & 255) << 24 | (data[offset + 1] & 255) << 16 | (data[offset] & 255) << 8 | data[offset + 3] & 255;
			break;
		case 0:
			pixel = (data[offset] & 255) << 24 | (data[offset + 1] & 255) << 16 | (data[offset + 2] & 255) << 8 | data[offset + 3] & 255;
			break;
		case 1:
			pixel = (data[offset + 1] & 255) << 24 | (data[offset + 2] & 255) << 16 | (data[offset + 3] & 255) << 8 | data[offset] & 255;
			break;
		}
		if((pixel & 255) != 0 && (pixel & 255) != 255) {
			lime_math_color__$RGBA_RGBA_$Impl_$.unmult = 255.0 / (pixel & 255);
			var r;
			var idx = Math.round((pixel >> 24 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.unmult);
			r = lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[idx];
			var g;
			var idx1 = Math.round((pixel >> 16 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.unmult);
			g = lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[idx1];
			var b;
			var idx2 = Math.round((pixel >> 8 & 255) * lime_math_color__$RGBA_RGBA_$Impl_$.unmult);
			b = lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[idx2];
			pixel = (r & 255) << 24 | (g & 255) << 16 | (b & 255) << 8 | pixel & 255 & 255;
		}
		var offset1 = i * 4;
		switch(format) {
		case 2:
			data[offset1] = pixel >> 8 & 255;
			data[offset1 + 1] = pixel >> 16 & 255;
			data[offset1 + 2] = pixel >> 24 & 255;
			data[offset1 + 3] = pixel & 255;
			break;
		case 0:
			data[offset1] = pixel >> 24 & 255;
			data[offset1 + 1] = pixel >> 16 & 255;
			data[offset1 + 2] = pixel >> 8 & 255;
			data[offset1 + 3] = pixel & 255;
			break;
		case 1:
			data[offset1] = pixel & 255;
			data[offset1 + 1] = pixel >> 24 & 255;
			data[offset1 + 2] = pixel >> 16 & 255;
			data[offset1 + 3] = pixel >> 8 & 255;
			break;
		}
	}
	image.buffer.premultiplied = false;
	image.dirty = true;
};
var lime_graphics_utils__$ImageDataUtil_ImageDataView = function(image,rect) {
	this.image = image;
	if(rect == null) this.rect = image.get_rect(); else {
		if(rect.x < 0) rect.x = 0;
		if(rect.y < 0) rect.y = 0;
		if(rect.x + rect.width > image.width) rect.width = image.width - rect.x;
		if(rect.y + rect.height > image.height) rect.height = image.height - rect.y;
		if(rect.width < 0) rect.width = 0;
		if(rect.height < 0) rect.height = 0;
		this.rect = rect;
	}
	this.stride = image.buffer.get_stride();
	this.x = Math.ceil(this.rect.x);
	this.y = Math.ceil(this.rect.y);
	this.width = Math.floor(this.rect.width);
	this.height = Math.floor(this.rect.height);
	this.offset = this.stride * (this.y + image.offsetY) + (this.x + image.offsetX) * 4;
};
$hxClasses["lime.graphics.utils._ImageDataUtil.ImageDataView"] = lime_graphics_utils__$ImageDataUtil_ImageDataView;
lime_graphics_utils__$ImageDataUtil_ImageDataView.__name__ = true;
lime_graphics_utils__$ImageDataUtil_ImageDataView.prototype = {
	__class__: lime_graphics_utils__$ImageDataUtil_ImageDataView
};
var lime_math__$ColorMatrix_ColorMatrix_$Impl_$ = {};
$hxClasses["lime.math._ColorMatrix.ColorMatrix_Impl_"] = lime_math__$ColorMatrix_ColorMatrix_$Impl_$;
lime_math__$ColorMatrix_ColorMatrix_$Impl_$.__name__ = true;
lime_math__$ColorMatrix_ColorMatrix_$Impl_$.getAlphaTable = function(this1) {
	var table;
	var this2;
	this2 = new Uint8Array(256);
	table = this2;
	var multiplier = this1[18];
	var offset = this1[19] * 255;
	var value;
	var _g = 0;
	while(_g < 256) {
		var i = _g++;
		value = Math.floor(i * multiplier + offset);
		if(value > 255) value = 255;
		if(value < 0) value = 0;
		table[i] = value;
	}
	return table;
};
lime_math__$ColorMatrix_ColorMatrix_$Impl_$.getBlueTable = function(this1) {
	var table;
	var this2;
	this2 = new Uint8Array(256);
	table = this2;
	var multiplier = this1[12];
	var offset = this1[14] * 255;
	var value;
	var _g = 0;
	while(_g < 256) {
		var i = _g++;
		value = Math.floor(i * multiplier + offset);
		if(value > 255) value = 255;
		if(value < 0) value = 0;
		table[i] = value;
	}
	return table;
};
lime_math__$ColorMatrix_ColorMatrix_$Impl_$.getGreenTable = function(this1) {
	var table;
	var this2;
	this2 = new Uint8Array(256);
	table = this2;
	var multiplier = this1[6];
	var offset = this1[9] * 255;
	var value;
	var _g = 0;
	while(_g < 256) {
		var i = _g++;
		value = Math.floor(i * multiplier + offset);
		if(value > 255) value = 255;
		if(value < 0) value = 0;
		table[i] = value;
	}
	return table;
};
lime_math__$ColorMatrix_ColorMatrix_$Impl_$.getRedTable = function(this1) {
	var table;
	var this2;
	this2 = new Uint8Array(256);
	table = this2;
	var multiplier = this1[0];
	var offset = this1[4] * 255;
	var value;
	var _g = 0;
	while(_g < 256) {
		var i = _g++;
		value = Math.floor(i * multiplier + offset);
		if(value > 255) value = 255;
		if(value < 0) value = 0;
		table[i] = value;
	}
	return table;
};
lime_math__$ColorMatrix_ColorMatrix_$Impl_$.__toFlashColorTransform = function(this1) {
	return null;
};
var lime_math_Matrix3 = function(a,b,c,d,tx,ty) {
	if(ty == null) ty = 0;
	if(tx == null) tx = 0;
	if(d == null) d = 1;
	if(c == null) c = 0;
	if(b == null) b = 0;
	if(a == null) a = 1;
	this.a = a;
	this.b = b;
	this.c = c;
	this.d = d;
	this.tx = tx;
	this.ty = ty;
};
$hxClasses["lime.math.Matrix3"] = lime_math_Matrix3;
lime_math_Matrix3.__name__ = true;
lime_math_Matrix3.prototype = {
	__class__: lime_math_Matrix3
};
var lime_math_Rectangle = function(x,y,width,height) {
	if(height == null) height = 0;
	if(width == null) width = 0;
	if(y == null) y = 0;
	if(x == null) x = 0;
	this.x = x;
	this.y = y;
	this.width = width;
	this.height = height;
};
$hxClasses["lime.math.Rectangle"] = lime_math_Rectangle;
lime_math_Rectangle.__name__ = true;
lime_math_Rectangle.prototype = {
	offset: function(dx,dy) {
		this.x += dx;
		this.y += dy;
	}
	,__toFlashRectangle: function() {
		return null;
	}
	,__class__: lime_math_Rectangle
};
var lime_math_Vector2 = function(x,y) {
	if(y == null) y = 0;
	if(x == null) x = 0;
	this.x = x;
	this.y = y;
};
$hxClasses["lime.math.Vector2"] = lime_math_Vector2;
lime_math_Vector2.__name__ = true;
lime_math_Vector2.prototype = {
	__class__: lime_math_Vector2
};
var lime_math_Vector4 = function() { };
$hxClasses["lime.math.Vector4"] = lime_math_Vector4;
lime_math_Vector4.__name__ = true;
var lime_math_color__$RGBA_RGBA_$Impl_$ = {};
$hxClasses["lime.math.color._RGBA.RGBA_Impl_"] = lime_math_color__$RGBA_RGBA_$Impl_$;
lime_math_color__$RGBA_RGBA_$Impl_$.__name__ = true;
lime_math_color__$RGBA_RGBA_$Impl_$.__alpha16 = null;
lime_math_color__$RGBA_RGBA_$Impl_$.__clamp = null;
lime_math_color__$RGBA_RGBA_$Impl_$.a16 = null;
lime_math_color__$RGBA_RGBA_$Impl_$.unmult = null;
var lime_net_URLLoader = function(request) {
	this.onSecurityError = new lime_app_Event_$lime_$net_$URLLoader_$String_$Void();
	this.onProgress = new lime_app_Event_$lime_$net_$URLLoader_$Int_$Int_$Void();
	this.onOpen = new lime_app_Event_$lime_$net_$URLLoader_$Void();
	this.onIOError = new lime_app_Event_$lime_$net_$URLLoader_$String_$Void();
	this.onHTTPStatus = new lime_app_Event_$lime_$net_$URLLoader_$Int_$Void();
	this.onComplete = new lime_app_Event_$lime_$net_$URLLoader_$Void();
	this.bytesLoaded = 0;
	this.bytesTotal = 0;
	this.set_dataFormat(lime_net_URLLoaderDataFormat.TEXT);
	if(request != null) this.load(request);
};
$hxClasses["lime.net.URLLoader"] = lime_net_URLLoader;
lime_net_URLLoader.__name__ = true;
lime_net_URLLoader.prototype = {
	getData: function() {
		return null;
	}
	,load: function(request) {
		this.requestUrl(request.url,request.method,request.data,request.formatRequestHeaders());
	}
	,registerEvents: function(subject) {
		var _g = this;
		var self = this;
		if(typeof XMLHttpRequestProgressEvent != "undefined") subject.addEventListener("progress",$bind(this,this.__onProgress),false);
		subject.onreadystatechange = function() {
			if(subject.readyState != 4) return;
			var s;
			try {
				s = subject.status;
			} catch( e ) {
				if (e instanceof js__$Boot_HaxeError) e = e.val;
				s = null;
			}
			if(s == undefined) s = null;
			if(s != null) self.onHTTPStatus.dispatch(_g,s);
			if(s != null && s >= 200 && s < 400) self.__onData(subject.response); else if(s == null) self.onIOError.dispatch(_g,"Failed to connect or resolve host"); else if(s == 12029) self.onIOError.dispatch(_g,"Failed to connect to host"); else if(s == 12007) self.onIOError.dispatch(_g,"Unknown host"); else if(s == 0) {
				self.onIOError.dispatch(_g,"Unable to make request (may be blocked due to cross-domain permissions)");
				self.onSecurityError.dispatch(_g,"Unable to make request (may be blocked due to cross-domain permissions)");
			} else self.onIOError.dispatch(_g,"Http Error #" + subject.status);
		};
	}
	,requestUrl: function(url,method,data,requestHeaders) {
		var xmlHttpRequest = new XMLHttpRequest();
		this.registerEvents(xmlHttpRequest);
		var uri = "";
		if(js_Boot.__instanceof(data,lime_utils_ByteArray)) {
			var data1 = data;
			var _g = this.dataFormat;
			switch(_g[1]) {
			case 0:
				uri = data1.data.buffer;
				break;
			default:
				uri = data1.readUTFBytes(data1.length);
			}
		} else if(js_Boot.__instanceof(data,lime_net_URLVariables)) {
			var data2 = data;
			var _g1 = 0;
			var _g11 = Reflect.fields(data2);
			while(_g1 < _g11.length) {
				var p = _g11[_g1];
				++_g1;
				if(uri.length != 0) uri += "&";
				uri += encodeURIComponent(p) + "=" + StringTools.urlEncode(Reflect.field(data2,p));
			}
		} else if(data != null) uri = data.toString();
		try {
			if(method == "GET" && uri != null && uri != "") {
				var question = url.split("?").length <= 1;
				xmlHttpRequest.open("GET",url + (question?"?":"&") + Std.string(uri),true);
				uri = "";
			} else xmlHttpRequest.open(js_Boot.__cast(method , String),url,true);
		} catch( e ) {
			if (e instanceof js__$Boot_HaxeError) e = e.val;
			this.onIOError.dispatch(this,e.toString());
			return;
		}
		var _g2 = this.dataFormat;
		switch(_g2[1]) {
		case 0:
			xmlHttpRequest.responseType = "arraybuffer";
			break;
		default:
		}
		var _g3 = 0;
		while(_g3 < requestHeaders.length) {
			var header = requestHeaders[_g3];
			++_g3;
			xmlHttpRequest.setRequestHeader(header.name,header.value);
		}
		xmlHttpRequest.send(uri);
		this.onOpen.dispatch(this);
		this.getData = function() {
			if(xmlHttpRequest.response != null) return xmlHttpRequest.response; else return xmlHttpRequest.responseText;
		};
	}
	,__onData: function(_) {
		var content = this.getData();
		var _g = this.dataFormat;
		switch(_g[1]) {
		case 0:
			this.data = lime_utils_ByteArray.__ofBuffer(content);
			break;
		default:
			this.data = Std.string(content);
		}
		this.onComplete.dispatch(this);
	}
	,__onProgress: function(event) {
		this.bytesLoaded = event.loaded;
		this.bytesTotal = event.total;
		this.onProgress.dispatch(this,this.bytesLoaded,this.bytesTotal);
	}
	,set_dataFormat: function(inputVal) {
		if(inputVal == lime_net_URLLoaderDataFormat.BINARY && !Reflect.hasField(window,"ArrayBuffer")) this.dataFormat = lime_net_URLLoaderDataFormat.TEXT; else this.dataFormat = inputVal;
		return this.dataFormat;
	}
	,__class__: lime_net_URLLoader
	,__properties__: {set_dataFormat:"set_dataFormat"}
};
var lime_net_URLLoaderDataFormat = $hxClasses["lime.net.URLLoaderDataFormat"] = { __ename__ : true, __constructs__ : ["BINARY","TEXT","VARIABLES"] };
lime_net_URLLoaderDataFormat.BINARY = ["BINARY",0];
lime_net_URLLoaderDataFormat.BINARY.toString = $estr;
lime_net_URLLoaderDataFormat.BINARY.__enum__ = lime_net_URLLoaderDataFormat;
lime_net_URLLoaderDataFormat.TEXT = ["TEXT",1];
lime_net_URLLoaderDataFormat.TEXT.toString = $estr;
lime_net_URLLoaderDataFormat.TEXT.__enum__ = lime_net_URLLoaderDataFormat;
lime_net_URLLoaderDataFormat.VARIABLES = ["VARIABLES",2];
lime_net_URLLoaderDataFormat.VARIABLES.toString = $estr;
lime_net_URLLoaderDataFormat.VARIABLES.__enum__ = lime_net_URLLoaderDataFormat;
var lime_net_URLRequest = function(inURL) {
	if(inURL != null) this.url = inURL;
	this.requestHeaders = [];
	this.method = "GET";
	this.contentType = null;
};
$hxClasses["lime.net.URLRequest"] = lime_net_URLRequest;
lime_net_URLRequest.__name__ = true;
lime_net_URLRequest.prototype = {
	formatRequestHeaders: function() {
		var res = this.requestHeaders;
		if(res == null) res = [];
		if(this.method == "GET" || this.data == null) return res;
		if(typeof(this.data) == "string" || js_Boot.__instanceof(this.data,lime_utils_ByteArray)) {
			res = res.slice();
			res.push(new lime_net_URLRequestHeader("Content-Type",this.contentType != null?this.contentType:"application/x-www-form-urlencoded"));
		}
		return res;
	}
	,__class__: lime_net_URLRequest
};
var lime_net_URLRequestHeader = function(name,value) {
	if(value == null) value = "";
	if(name == null) name = "";
	this.name = name;
	this.value = value;
};
$hxClasses["lime.net.URLRequestHeader"] = lime_net_URLRequestHeader;
lime_net_URLRequestHeader.__name__ = true;
lime_net_URLRequestHeader.prototype = {
	__class__: lime_net_URLRequestHeader
};
var lime_net_URLVariables = function() { };
$hxClasses["lime.net.URLVariables"] = lime_net_URLVariables;
lime_net_URLVariables.__name__ = true;
var lime_system_Clipboard = function() { };
$hxClasses["lime.system.Clipboard"] = lime_system_Clipboard;
lime_system_Clipboard.__name__ = true;
lime_system_Clipboard.__properties__ = {set_text:"set_text",get_text:"get_text"}
lime_system_Clipboard.get_text = function() {
	return null;
};
lime_system_Clipboard.set_text = function(value) {
	return null;
};
var lime_system_System = function() { };
$hxClasses["lime.system.System"] = lime_system_System;
lime_system_System.__name__ = true;
lime_system_System.embed = $hx_exports.lime.embed = function(element,width,height,background,assetsPrefix) {
	var htmlElement = null;
	if(typeof(element) == "string") htmlElement = window.document.getElementById(js_Boot.__cast(element , String)); else if(element == null) htmlElement = window.document.createElement("div"); else htmlElement = element;
	var color = null;
	if(background != null) {
		background = StringTools.replace(background,"#","");
		if(background.indexOf("0x") > -1) color = Std.parseInt(background); else color = Std.parseInt("0x" + background);
	}
	if(width == null) width = 0;
	if(height == null) height = 0;
	ApplicationMain.config.windows[0].background = color;
	ApplicationMain.config.windows[0].element = htmlElement;
	ApplicationMain.config.windows[0].width = width;
	ApplicationMain.config.windows[0].height = height;
	ApplicationMain.config.assetsPrefix = assetsPrefix;
	ApplicationMain.create();
};
lime_system_System.getTimer = function() {
	return new Date().getTime();
};
var lime_ui_Gamepad = function() {
	this.onDisconnect = new lime_app_Event_$Void_$Void();
	this.onButtonUp = new lime_app_Event_$lime_$ui_$GamepadButton_$Void();
	this.onButtonDown = new lime_app_Event_$lime_$ui_$GamepadButton_$Void();
	this.onAxisMove = new lime_app_Event_$lime_$ui_$GamepadAxis_$Float_$Void();
};
$hxClasses["lime.ui.Gamepad"] = lime_ui_Gamepad;
lime_ui_Gamepad.__name__ = true;
lime_ui_Gamepad.prototype = {
	__class__: lime_ui_Gamepad
};
var lime_ui_Joystick = function() {
	this.onTrackballMove = new lime_app_Event_$Int_$Float_$Void();
	this.onHatMove = new lime_app_Event_$Int_$lime_$ui_$JoystickHatPosition_$Void();
	this.onDisconnect = new lime_app_Event_$Void_$Void();
	this.onButtonUp = new lime_app_Event_$Int_$Void();
	this.onButtonDown = new lime_app_Event_$Int_$Void();
	this.onAxisMove = new lime_app_Event_$Int_$Float_$Void();
};
$hxClasses["lime.ui.Joystick"] = lime_ui_Joystick;
lime_ui_Joystick.__name__ = true;
lime_ui_Joystick.prototype = {
	__class__: lime_ui_Joystick
};
var lime_ui__$KeyModifier_KeyModifier_$Impl_$ = {};
$hxClasses["lime.ui._KeyModifier.KeyModifier_Impl_"] = lime_ui__$KeyModifier_KeyModifier_$Impl_$;
lime_ui__$KeyModifier_KeyModifier_$Impl_$.__name__ = true;
lime_ui__$KeyModifier_KeyModifier_$Impl_$.__properties__ = {get_shiftKey:"get_shiftKey",get_metaKey:"get_metaKey",get_ctrlKey:"get_ctrlKey",get_altKey:"get_altKey"}
lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_altKey = function(this1) {
	return (this1 & 256) > 0 || (this1 & 512) > 0;
};
lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_ctrlKey = function(this1) {
	return (this1 & 64) > 0 || (this1 & 128) > 0;
};
lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_metaKey = function(this1) {
	return (this1 & 1024) > 0 || (this1 & 2048) > 0;
};
lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_shiftKey = function(this1) {
	return (this1 & 1) > 0 || (this1 & 2) > 0;
};
var lime_ui_Mouse = function() { };
$hxClasses["lime.ui.Mouse"] = lime_ui_Mouse;
lime_ui_Mouse.__name__ = true;
lime_ui_Mouse.__properties__ = {set_cursor:"set_cursor"}
lime_ui_Mouse.set_cursor = function(value) {
	return lime__$backend_html5_HTML5Mouse.set_cursor(value);
};
var lime_ui_MouseCursor = $hxClasses["lime.ui.MouseCursor"] = { __ename__ : true, __constructs__ : ["ARROW","CROSSHAIR","DEFAULT","MOVE","POINTER","RESIZE_NESW","RESIZE_NS","RESIZE_NWSE","RESIZE_WE","TEXT","WAIT","WAIT_ARROW","CUSTOM"] };
lime_ui_MouseCursor.ARROW = ["ARROW",0];
lime_ui_MouseCursor.ARROW.toString = $estr;
lime_ui_MouseCursor.ARROW.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.CROSSHAIR = ["CROSSHAIR",1];
lime_ui_MouseCursor.CROSSHAIR.toString = $estr;
lime_ui_MouseCursor.CROSSHAIR.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.DEFAULT = ["DEFAULT",2];
lime_ui_MouseCursor.DEFAULT.toString = $estr;
lime_ui_MouseCursor.DEFAULT.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.MOVE = ["MOVE",3];
lime_ui_MouseCursor.MOVE.toString = $estr;
lime_ui_MouseCursor.MOVE.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.POINTER = ["POINTER",4];
lime_ui_MouseCursor.POINTER.toString = $estr;
lime_ui_MouseCursor.POINTER.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.RESIZE_NESW = ["RESIZE_NESW",5];
lime_ui_MouseCursor.RESIZE_NESW.toString = $estr;
lime_ui_MouseCursor.RESIZE_NESW.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.RESIZE_NS = ["RESIZE_NS",6];
lime_ui_MouseCursor.RESIZE_NS.toString = $estr;
lime_ui_MouseCursor.RESIZE_NS.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.RESIZE_NWSE = ["RESIZE_NWSE",7];
lime_ui_MouseCursor.RESIZE_NWSE.toString = $estr;
lime_ui_MouseCursor.RESIZE_NWSE.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.RESIZE_WE = ["RESIZE_WE",8];
lime_ui_MouseCursor.RESIZE_WE.toString = $estr;
lime_ui_MouseCursor.RESIZE_WE.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.TEXT = ["TEXT",9];
lime_ui_MouseCursor.TEXT.toString = $estr;
lime_ui_MouseCursor.TEXT.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.WAIT = ["WAIT",10];
lime_ui_MouseCursor.WAIT.toString = $estr;
lime_ui_MouseCursor.WAIT.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.WAIT_ARROW = ["WAIT_ARROW",11];
lime_ui_MouseCursor.WAIT_ARROW.toString = $estr;
lime_ui_MouseCursor.WAIT_ARROW.__enum__ = lime_ui_MouseCursor;
lime_ui_MouseCursor.CUSTOM = ["CUSTOM",12];
lime_ui_MouseCursor.CUSTOM.toString = $estr;
lime_ui_MouseCursor.CUSTOM.__enum__ = lime_ui_MouseCursor;
var lime_ui_Touch = function(x,y,id,dx,dy,pressure,device) {
	this.x = x;
	this.y = y;
	this.id = id;
	this.dx = dx;
	this.dy = dy;
	this.pressure = pressure;
	this.device = device;
};
$hxClasses["lime.ui.Touch"] = lime_ui_Touch;
lime_ui_Touch.__name__ = true;
lime_ui_Touch.prototype = {
	__class__: lime_ui_Touch
};
var lime_ui_Window = function(config) {
	this.onTextInput = new lime_app_Event_$String_$Void();
	this.onTextEdit = new lime_app_Event_$String_$Int_$Int_$Void();
	this.onRestore = new lime_app_Event_$Void_$Void();
	this.onResize = new lime_app_Event_$Int_$Int_$Void();
	this.onMove = new lime_app_Event_$Float_$Float_$Void();
	this.onMouseWheel = new lime_app_Event_$Float_$Float_$Void();
	this.onMouseUp = new lime_app_Event_$Float_$Float_$Int_$Void();
	this.onMouseMoveRelative = new lime_app_Event_$Float_$Float_$Void();
	this.onMouseMove = new lime_app_Event_$Float_$Float_$Void();
	this.onMouseDown = new lime_app_Event_$Float_$Float_$Int_$Void();
	this.onMinimize = new lime_app_Event_$Void_$Void();
	this.onLeave = new lime_app_Event_$Void_$Void();
	this.onKeyUp = new lime_app_Event_$lime_$ui_$KeyCode_$lime_$ui_$KeyModifier_$Void();
	this.onKeyDown = new lime_app_Event_$lime_$ui_$KeyCode_$lime_$ui_$KeyModifier_$Void();
	this.onFullscreen = new lime_app_Event_$Void_$Void();
	this.onFocusOut = new lime_app_Event_$Void_$Void();
	this.onFocusIn = new lime_app_Event_$Void_$Void();
	this.onEnter = new lime_app_Event_$Void_$Void();
	this.onDeactivate = new lime_app_Event_$Void_$Void();
	this.onCreate = new lime_app_Event_$Void_$Void();
	this.onClose = new lime_app_Event_$Void_$Void();
	this.onActivate = new lime_app_Event_$Void_$Void();
	this.config = config;
	this.__width = 0;
	this.__height = 0;
	this.__fullscreen = false;
	this.__scale = 1;
	this.__x = 0;
	this.__y = 0;
	this.__title = "";
	this.id = -1;
	if(config != null) {
		if(Object.prototype.hasOwnProperty.call(config,"width")) this.__width = config.width;
		if(Object.prototype.hasOwnProperty.call(config,"height")) this.__height = config.height;
		if(Object.prototype.hasOwnProperty.call(config,"x")) this.__x = config.x;
		if(Object.prototype.hasOwnProperty.call(config,"y")) this.__y = config.y;
		if(Object.prototype.hasOwnProperty.call(config,"fullscreen")) this.__fullscreen = config.fullscreen;
		if(Object.prototype.hasOwnProperty.call(config,"title")) this.__title = config.title;
	}
	this.backend = new lime__$backend_html5_HTML5Window(this);
};
$hxClasses["lime.ui.Window"] = lime_ui_Window;
lime_ui_Window.__name__ = true;
lime_ui_Window.prototype = {
	close: function() {
		this.backend.close();
	}
	,create: function(application) {
		this.application = application;
		this.backend.create(application);
		if(this.renderer != null) this.renderer.create();
	}
	,resize: function(width,height) {
		this.backend.resize(width,height);
		this.__width = width;
		this.__height = height;
	}
	,set_fullscreen: function(value) {
		return this.__fullscreen = this.backend.setFullscreen(value);
	}
	,set_height: function(value) {
		this.resize(this.__width,value);
		return this.__height;
	}
	,set_width: function(value) {
		this.resize(value,this.__height);
		return this.__width;
	}
	,__class__: lime_ui_Window
	,__properties__: {set_width:"set_width",set_height:"set_height",set_fullscreen:"set_fullscreen"}
};
var lime_utils_ByteArray = function(size) {
	if(size == null) size = 0;
	this.allocated = 0;
	this.position = 0;
	this.length = 0;
	if(size > 0) this.allocated = size;
	this.___resizeBuffer(this.allocated);
	this.set_length(this.allocated);
};
$hxClasses["lime.utils.ByteArray"] = lime_utils_ByteArray;
lime_utils_ByteArray.__name__ = true;
lime_utils_ByteArray.__ofBuffer = function(buffer) {
	var bytes = new lime_utils_ByteArray();
	bytes.set_length(bytes.allocated = buffer.byteLength);
	bytes.data = new DataView(buffer);
	bytes.byteView = new Uint8Array(buffer);
	return bytes;
};
lime_utils_ByteArray.prototype = {
	readByte: function() {
		var data = this.data;
		return data.getInt8(this.position++);
	}
	,readUnsignedByte: function() {
		var data = this.data;
		return data.getUint8(this.position++);
	}
	,readUTFBytes: function(len) {
		var value = "";
		var max = this.position + len;
		while(this.position < max) {
			var data = this.data;
			var c = data.getUint8(this.position++);
			if(c < 128) {
				if(c == 0) break;
				value += String.fromCharCode(c);
			} else if(c < 224) value += String.fromCharCode((c & 63) << 6 | data.getUint8(this.position++) & 127); else if(c < 240) {
				var c2 = data.getUint8(this.position++);
				value += String.fromCharCode((c & 31) << 12 | (c2 & 127) << 6 | data.getUint8(this.position++) & 127);
			} else {
				var c21 = data.getUint8(this.position++);
				var c3 = data.getUint8(this.position++);
				value += String.fromCharCode((c & 15) << 18 | (c21 & 127) << 12 | c3 << 6 & 127 | data.getUint8(this.position++) & 127);
			}
		}
		return value;
	}
	,__get: function(pos) {
		return this.data.getInt8(pos);
	}
	,___resizeBuffer: function(len) {
		var oldByteView = this.byteView;
		var newByteView = new Uint8Array(len);
		if(oldByteView != null) {
			if(oldByteView.length <= len) newByteView.set(oldByteView); else newByteView.set(oldByteView.subarray(0,len));
		}
		this.byteView = newByteView;
		this.data = new DataView(newByteView.buffer);
	}
	,__set: function(pos,v) {
		this.data.setUint8(pos,v);
	}
	,set_length: function(value) {
		if(this.allocated < value) this.___resizeBuffer(this.allocated = Std["int"](Math.max(value,this.allocated * 2))); else if(this.allocated > value * 2) this.___resizeBuffer(this.allocated = value);
		this.length = value;
		return value;
	}
	,__class__: lime_utils_ByteArray
	,__properties__: {set_length:"set_length"}
};
var motion_actuators_IGenericActuator = function() { };
$hxClasses["motion.actuators.IGenericActuator"] = motion_actuators_IGenericActuator;
motion_actuators_IGenericActuator.__name__ = true;
motion_actuators_IGenericActuator.prototype = {
	__class__: motion_actuators_IGenericActuator
};
var motion_actuators_GenericActuator = function(target,duration,properties) {
	this._autoVisible = true;
	this._delay = 0;
	this._reflect = false;
	this._repeat = 0;
	this._reverse = false;
	this._smartRotation = false;
	this._snapping = false;
	this.special = false;
	this.target = target;
	this.properties = properties;
	this.duration = duration;
	this._ease = motion_Actuate.defaultEase;
};
$hxClasses["motion.actuators.GenericActuator"] = motion_actuators_GenericActuator;
motion_actuators_GenericActuator.__name__ = true;
motion_actuators_GenericActuator.__interfaces__ = [motion_actuators_IGenericActuator];
motion_actuators_GenericActuator.prototype = {
	apply: function() {
		var _g = 0;
		var _g1 = Reflect.fields(this.properties);
		while(_g < _g1.length) {
			var i = _g1[_g];
			++_g;
			if(Object.prototype.hasOwnProperty.call(this.target,i)) Reflect.setField(this.target,i,Reflect.field(this.properties,i)); else Reflect.setProperty(this.target,i,Reflect.field(this.properties,i));
		}
	}
	,autoVisible: function(value) {
		if(value == null) value = true;
		this._autoVisible = value;
		return this;
	}
	,callMethod: function(method,params) {
		if(params == null) params = [];
		return Reflect.callMethod(method,method,params);
	}
	,change: function() {
		if(this._onUpdate != null) this.callMethod(this._onUpdate,this._onUpdateParams);
	}
	,complete: function(sendEvent) {
		if(sendEvent == null) sendEvent = true;
		if(sendEvent) {
			this.change();
			if(this._onComplete != null) this.callMethod(this._onComplete,this._onCompleteParams);
		}
		motion_Actuate.unload(this);
	}
	,delay: function(duration) {
		this._delay = duration;
		return this;
	}
	,ease: function(easing) {
		this._ease = easing;
		return this;
	}
	,move: function() {
	}
	,onComplete: function(handler,parameters) {
		this._onComplete = handler;
		if(parameters == null) this._onCompleteParams = []; else this._onCompleteParams = parameters;
		if(this.duration == 0) this.complete();
		return this;
	}
	,onRepeat: function(handler,parameters) {
		this._onRepeat = handler;
		if(parameters == null) this._onRepeatParams = []; else this._onRepeatParams = parameters;
		return this;
	}
	,onUpdate: function(handler,parameters) {
		this._onUpdate = handler;
		if(parameters == null) this._onUpdateParams = []; else this._onUpdateParams = parameters;
		return this;
	}
	,onPause: function(handler,parameters) {
		this._onPause = handler;
		if(parameters == null) this._onPauseParams = []; else this._onPauseParams = parameters;
		return this;
	}
	,onResume: function(handler,parameters) {
		this._onResume = handler;
		if(parameters == null) this._onResumeParams = []; else this._onResumeParams = parameters;
		return this;
	}
	,pause: function() {
		if(this._onPause != null) this.callMethod(this._onPause,this._onPauseParams);
	}
	,reflect: function(value) {
		if(value == null) value = true;
		this._reflect = value;
		this.special = true;
		return this;
	}
	,repeat: function(times) {
		if(times == null) times = -1;
		this._repeat = times;
		return this;
	}
	,resume: function() {
		if(this._onResume != null) this.callMethod(this._onResume,this._onResumeParams);
	}
	,reverse: function(value) {
		if(value == null) value = true;
		this._reverse = value;
		this.special = true;
		return this;
	}
	,smartRotation: function(value) {
		if(value == null) value = true;
		this._smartRotation = value;
		this.special = true;
		return this;
	}
	,snapping: function(value) {
		if(value == null) value = true;
		this._snapping = value;
		this.special = true;
		return this;
	}
	,stop: function(properties,complete,sendEvent) {
	}
	,__class__: motion_actuators_GenericActuator
};
var motion_actuators_SimpleActuator = function(target,duration,properties) {
	this.active = true;
	this.propertyDetails = [];
	this.sendChange = false;
	this.paused = false;
	this.cacheVisible = false;
	this.initialized = false;
	this.setVisible = false;
	this.toggleVisible = false;
	this.startTime = openfl_Lib.getTimer() / 1000;
	motion_actuators_GenericActuator.call(this,target,duration,properties);
	if(!motion_actuators_SimpleActuator.addedEvent) {
		motion_actuators_SimpleActuator.addedEvent = true;
		openfl_Lib.current.stage.addEventListener(openfl_events_Event.ENTER_FRAME,motion_actuators_SimpleActuator.stage_onEnterFrame);
	}
};
$hxClasses["motion.actuators.SimpleActuator"] = motion_actuators_SimpleActuator;
motion_actuators_SimpleActuator.__name__ = true;
motion_actuators_SimpleActuator.stage_onEnterFrame = function(event) {
	var currentTime = openfl_Lib.getTimer() / 1000;
	var actuator;
	var j = 0;
	var cleanup = false;
	var _g1 = 0;
	var _g = motion_actuators_SimpleActuator.actuatorsLength;
	while(_g1 < _g) {
		var i = _g1++;
		actuator = motion_actuators_SimpleActuator.actuators[j];
		if(actuator != null && actuator.active) {
			if(currentTime >= actuator.timeOffset) actuator.update(currentTime);
			j++;
		} else {
			motion_actuators_SimpleActuator.actuators.splice(j,1);
			--motion_actuators_SimpleActuator.actuatorsLength;
		}
	}
};
motion_actuators_SimpleActuator.__super__ = motion_actuators_GenericActuator;
motion_actuators_SimpleActuator.prototype = $extend(motion_actuators_GenericActuator.prototype,{
	setField_openfl_geom_Transform: function(target,propertyName,value) {
		if(Object.prototype.hasOwnProperty.call(target,propertyName)) target[propertyName] = value; else Reflect.setProperty(target,propertyName,value);
	}
	,setField_motion_actuators_TransformActuator_T: function(target,propertyName,value) {
		if(Object.prototype.hasOwnProperty.call(target,propertyName)) target[propertyName] = value; else Reflect.setProperty(target,propertyName,value);
	}
	,setField_motion_actuators_MotionPathActuator_T: function(target,propertyName,value) {
		if(Object.prototype.hasOwnProperty.call(target,propertyName)) target[propertyName] = value; else Reflect.setProperty(target,propertyName,value);
	}
	,setField_openfl_display_DisplayObject: function(target,propertyName,value) {
		if(Object.prototype.hasOwnProperty.call(target,propertyName)) target[propertyName] = value; else Reflect.setProperty(target,propertyName,value);
	}
	,setField_motion_actuators_SimpleActuator_T: function(target,propertyName,value) {
		if(Object.prototype.hasOwnProperty.call(target,propertyName)) target[propertyName] = value; else Reflect.setProperty(target,propertyName,value);
	}
	,autoVisible: function(value) {
		if(value == null) value = true;
		this._autoVisible = value;
		if(!value) {
			this.toggleVisible = false;
			if(this.setVisible) this.setField_motion_actuators_SimpleActuator_T(this.target,"visible",this.cacheVisible);
		}
		return this;
	}
	,delay: function(duration) {
		this._delay = duration;
		this.timeOffset = this.startTime + duration;
		return this;
	}
	,getField: function(target,propertyName) {
		var value = null;
		if(Object.prototype.hasOwnProperty.call(target,propertyName)) value = Reflect.field(target,propertyName); else value = Reflect.getProperty(target,propertyName);
		return value;
	}
	,initialize: function() {
		var details;
		var start;
		var _g = 0;
		var _g1 = Reflect.fields(this.properties);
		while(_g < _g1.length) {
			var i = _g1[_g];
			++_g;
			var isField = true;
			if(Object.prototype.hasOwnProperty.call(this.target,i) && !(this.target.__properties__ && this.target.__properties__["set_" + i])) start = Reflect.field(this.target,i); else {
				isField = false;
				start = Reflect.getProperty(this.target,i);
			}
			if(typeof(start) == "number") {
				var value = this.getField(this.properties,i);
				if(start == null) start = 0;
				if(value == null) value = 0;
				details = new motion_actuators_PropertyDetails(this.target,i,start,value - start,isField);
				this.propertyDetails.push(details);
			}
		}
		this.detailsLength = this.propertyDetails.length;
		this.initialized = true;
	}
	,move: function() {
		this.toggleVisible = Object.prototype.hasOwnProperty.call(this.properties,"alpha") && js_Boot.__instanceof(this.target,openfl_display_DisplayObject);
		if(this.toggleVisible && this.properties.alpha != 0 && !this.getField(this.target,"visible")) {
			this.setVisible = true;
			this.cacheVisible = this.getField(this.target,"visible");
			this.setField_motion_actuators_SimpleActuator_T(this.target,"visible",true);
		}
		this.timeOffset = this.startTime;
		motion_actuators_SimpleActuator.actuators.push(this);
		++motion_actuators_SimpleActuator.actuatorsLength;
	}
	,onUpdate: function(handler,parameters) {
		this._onUpdate = handler;
		if(parameters == null) this._onUpdateParams = []; else this._onUpdateParams = parameters;
		this.sendChange = true;
		return this;
	}
	,pause: function() {
		if(!this.paused) {
			this.paused = true;
			motion_actuators_GenericActuator.prototype.pause.call(this);
			this.pauseTime = openfl_Lib.getTimer();
		}
	}
	,resume: function() {
		if(this.paused) {
			this.paused = false;
			this.timeOffset += (openfl_Lib.getTimer() - this.pauseTime) / 1000;
			motion_actuators_GenericActuator.prototype.resume.call(this);
		}
	}
	,setProperty: function(details,value) {
		if(details.isField) details.target[details.propertyName] = value; else Reflect.setProperty(details.target,details.propertyName,value);
	}
	,stop: function(properties,complete,sendEvent) {
		if(this.active) {
			if(properties == null) {
				this.active = false;
				if(complete) this.apply();
				this.complete(sendEvent);
				return;
			}
			var _g = 0;
			var _g1 = Reflect.fields(properties);
			while(_g < _g1.length) {
				var i = _g1[_g];
				++_g;
				if(Object.prototype.hasOwnProperty.call(this.properties,i)) {
					this.active = false;
					if(complete) this.apply();
					this.complete(sendEvent);
					return;
				}
			}
		}
	}
	,update: function(currentTime) {
		if(!this.paused) {
			var details;
			var easing;
			var i;
			var tweenPosition = (currentTime - this.timeOffset) / this.duration;
			if(tweenPosition > 1) tweenPosition = 1;
			if(!this.initialized) this.initialize();
			if(!this.special) {
				easing = this._ease.calculate(tweenPosition);
				var _g1 = 0;
				var _g = this.detailsLength;
				while(_g1 < _g) {
					var i1 = _g1++;
					details = this.propertyDetails[i1];
					this.setProperty(details,details.start + details.change * easing);
				}
			} else {
				if(!this._reverse) easing = this._ease.calculate(tweenPosition); else easing = this._ease.calculate(1 - tweenPosition);
				var endValue;
				var _g11 = 0;
				var _g2 = this.detailsLength;
				while(_g11 < _g2) {
					var i2 = _g11++;
					details = this.propertyDetails[i2];
					if(this._smartRotation && (details.propertyName == "rotation" || details.propertyName == "rotationX" || details.propertyName == "rotationY" || details.propertyName == "rotationZ")) {
						var rotation = details.change % 360;
						if(rotation > 180) rotation -= 360; else if(rotation < -180) rotation += 360;
						endValue = details.start + rotation * easing;
					} else endValue = details.start + details.change * easing;
					if(!this._snapping) {
						if(details.isField) details.target[details.propertyName] = endValue; else Reflect.setProperty(details.target,details.propertyName,endValue);
					} else this.setProperty(details,Math.round(endValue));
				}
			}
			if(tweenPosition == 1) {
				if(this._repeat == 0) {
					this.active = false;
					if(this.toggleVisible && this.getField(this.target,"alpha") == 0) this.setField_motion_actuators_SimpleActuator_T(this.target,"visible",false);
					this.complete(true);
					return;
				} else {
					if(this._onRepeat != null) this.callMethod(this._onRepeat,this._onRepeatParams);
					if(this._reflect) this._reverse = !this._reverse;
					this.startTime = currentTime;
					this.timeOffset = this.startTime + this._delay;
					if(this._repeat > 0) this._repeat--;
				}
			}
			if(this.sendChange) this.change();
		}
	}
	,__class__: motion_actuators_SimpleActuator
});
var motion_easing_Expo = function() { };
$hxClasses["motion.easing.Expo"] = motion_easing_Expo;
motion_easing_Expo.__name__ = true;
motion_easing_Expo.__properties__ = {get_easeOut:"get_easeOut"}
motion_easing_Expo.get_easeOut = function() {
	return new motion_easing_ExpoEaseOut();
};
var motion_easing_IEasing = function() { };
$hxClasses["motion.easing.IEasing"] = motion_easing_IEasing;
motion_easing_IEasing.__name__ = true;
motion_easing_IEasing.prototype = {
	__class__: motion_easing_IEasing
};
var motion_easing_ExpoEaseOut = function() {
};
$hxClasses["motion.easing.ExpoEaseOut"] = motion_easing_ExpoEaseOut;
motion_easing_ExpoEaseOut.__name__ = true;
motion_easing_ExpoEaseOut.__interfaces__ = [motion_easing_IEasing];
motion_easing_ExpoEaseOut.prototype = {
	calculate: function(k) {
		if(k == 1) return 1; else return 1 - Math.pow(2,-10 * k);
	}
	,__class__: motion_easing_ExpoEaseOut
};
var motion_Actuate = function() { };
$hxClasses["motion.Actuate"] = motion_Actuate;
motion_Actuate.__name__ = true;
motion_Actuate.apply = function(target,properties,customActuator) {
	motion_Actuate.stop(target,properties);
	if(customActuator == null) customActuator = motion_Actuate.defaultActuator;
	var actuator = Type.createInstance(customActuator,[target,0,properties]);
	actuator.apply();
	return actuator;
};
motion_Actuate.getLibrary = function(target,allowCreation) {
	if(allowCreation == null) allowCreation = true;
	if(!(motion_Actuate.targetLibraries.h.__keys__[target.__id__] != null) && allowCreation) motion_Actuate.targetLibraries.set(target,[]);
	return motion_Actuate.targetLibraries.h[target.__id__];
};
motion_Actuate.stop = function(target,properties,complete,sendEvent) {
	if(sendEvent == null) sendEvent = true;
	if(complete == null) complete = false;
	if(target != null) {
		if(js_Boot.__instanceof(target,motion_actuators_IGenericActuator)) {
			var actuator = target;
			actuator.stop(null,complete,sendEvent);
		} else {
			var library = motion_Actuate.getLibrary(target,false);
			if(library != null) {
				if(typeof(properties) == "string") {
					var temp = { };
					Reflect.setField(temp,properties,null);
					properties = temp;
				} else if((properties instanceof Array) && properties.__enum__ == null) {
					var temp1 = { };
					var _g = 0;
					var _g1;
					_g1 = js_Boot.__cast(properties , Array);
					while(_g < _g1.length) {
						var property = _g1[_g];
						++_g;
						Reflect.setField(temp1,property,null);
					}
					properties = temp1;
				}
				var i = library.length - 1;
				while(i >= 0) {
					library[i].stop(properties,complete,sendEvent);
					i--;
				}
			}
		}
	}
};
motion_Actuate.tween = function(target,duration,properties,overwrite,customActuator) {
	if(overwrite == null) overwrite = true;
	if(target != null) {
		if(duration > 0) {
			if(customActuator == null) customActuator = motion_Actuate.defaultActuator;
			var actuator = Type.createInstance(customActuator,[target,duration,properties]);
			var library = motion_Actuate.getLibrary(actuator.target);
			if(overwrite) {
				var i = library.length - 1;
				while(i >= 0) {
					library[i].stop(actuator.properties,false,false);
					i--;
				}
				library = motion_Actuate.getLibrary(actuator.target);
			}
			library.push(actuator);
			actuator.move();
			return actuator;
		} else return motion_Actuate.apply(target,properties,customActuator);
	}
	return null;
};
motion_Actuate.unload = function(actuator) {
	var target = actuator.target;
	if(motion_Actuate.targetLibraries.h.__keys__[target.__id__] != null) {
		HxOverrides.remove(motion_Actuate.targetLibraries.h[target.__id__],actuator);
		if(motion_Actuate.targetLibraries.h[target.__id__].length == 0) motion_Actuate.targetLibraries.remove(target);
	}
};
var motion_IComponentPath = function() { };
$hxClasses["motion.IComponentPath"] = motion_IComponentPath;
motion_IComponentPath.__name__ = true;
motion_IComponentPath.prototype = {
	__class__: motion_IComponentPath
	,__properties__: {get_end:"get_end"}
};
var motion_actuators_FilterActuator = function(target,duration,properties) {
	this.filterIndex = -1;
	motion_actuators_SimpleActuator.call(this,target,duration,properties);
	if(js_Boot.__instanceof(properties.filter,Class)) {
		this.filterClass = properties.filter;
		if(target.get_filters().length == 0) target.set_filters([Type.createInstance(this.filterClass,[])]);
		var _g = 0;
		var _g1 = target.get_filters();
		while(_g < _g1.length) {
			var filter = _g1[_g];
			++_g;
			if(js_Boot.__instanceof(filter,this.filterClass)) this.filter = filter;
		}
	} else {
		this.filterIndex = properties.filter;
		this.filter = target.get_filters()[this.filterIndex];
	}
};
$hxClasses["motion.actuators.FilterActuator"] = motion_actuators_FilterActuator;
motion_actuators_FilterActuator.__name__ = true;
motion_actuators_FilterActuator.__super__ = motion_actuators_SimpleActuator;
motion_actuators_FilterActuator.prototype = $extend(motion_actuators_SimpleActuator.prototype,{
	apply: function() {
		var _g = 0;
		var _g1 = Reflect.fields(this.properties);
		while(_g < _g1.length) {
			var propertyName = _g1[_g];
			++_g;
			if(propertyName != "filter") Reflect.setField(this.filter,propertyName,Reflect.field(this.properties,propertyName));
		}
		var filters = this.getField(this.target,"filters");
		Reflect.setField(filters,this.properties.filter,this.filter);
		this.setField_openfl_display_DisplayObject(this.target,"filters",filters);
	}
	,initialize: function() {
		var details;
		var start;
		var _g = 0;
		var _g1 = Reflect.fields(this.properties);
		while(_g < _g1.length) {
			var propertyName = _g1[_g];
			++_g;
			if(propertyName != "filter") {
				start = this.getField(this.filter,propertyName);
				details = new motion_actuators_PropertyDetails(this.filter,propertyName,start,Reflect.field(this.properties,propertyName) - start);
				this.propertyDetails.push(details);
			}
		}
		this.detailsLength = this.propertyDetails.length;
		this.initialized = true;
	}
	,update: function(currentTime) {
		motion_actuators_SimpleActuator.prototype.update.call(this,currentTime);
		var filters = this.target.get_filters();
		if(this.filterIndex > -1) Reflect.setField(filters,this.properties.filter,this.filter); else {
			var _g1 = 0;
			var _g = filters.length;
			while(_g1 < _g) {
				var i = _g1++;
				if(js_Boot.__instanceof(filters[i],this.filterClass)) filters[i] = this.filter;
			}
		}
		this.setField_openfl_display_DisplayObject(this.target,"filters",filters);
	}
	,__class__: motion_actuators_FilterActuator
});
var motion_actuators_MethodActuator = function(target,duration,properties) {
	this.currentParameters = [];
	this.tweenProperties = { };
	motion_actuators_SimpleActuator.call(this,target,duration,properties);
	if(!Object.prototype.hasOwnProperty.call(properties,"start")) this.properties.start = [];
	if(!Object.prototype.hasOwnProperty.call(properties,"end")) this.properties.end = this.properties.start;
	var _g1 = 0;
	var _g = this.properties.start.length;
	while(_g1 < _g) {
		var i = _g1++;
		this.currentParameters.push(this.properties.start[i]);
	}
};
$hxClasses["motion.actuators.MethodActuator"] = motion_actuators_MethodActuator;
motion_actuators_MethodActuator.__name__ = true;
motion_actuators_MethodActuator.__super__ = motion_actuators_SimpleActuator;
motion_actuators_MethodActuator.prototype = $extend(motion_actuators_SimpleActuator.prototype,{
	apply: function() {
		this.callMethod(this.target,this.properties.end);
	}
	,complete: function(sendEvent) {
		if(sendEvent == null) sendEvent = true;
		var _g1 = 0;
		var _g = this.properties.start.length;
		while(_g1 < _g) {
			var i = _g1++;
			this.currentParameters[i] = Reflect.field(this.tweenProperties,"param" + i);
		}
		this.callMethod(this.target,this.currentParameters);
		motion_actuators_SimpleActuator.prototype.complete.call(this,sendEvent);
	}
	,initialize: function() {
		var details;
		var propertyName;
		var start;
		var _g1 = 0;
		var _g = this.properties.start.length;
		while(_g1 < _g) {
			var i = _g1++;
			propertyName = "param" + i;
			start = this.properties.start[i];
			this.tweenProperties[propertyName] = start;
			if(typeof(start) == "number" || ((start | 0) === start)) {
				details = new motion_actuators_PropertyDetails(this.tweenProperties,propertyName,start,this.properties.end[i] - start);
				this.propertyDetails.push(details);
			}
		}
		this.detailsLength = this.propertyDetails.length;
		this.initialized = true;
	}
	,update: function(currentTime) {
		motion_actuators_SimpleActuator.prototype.update.call(this,currentTime);
		if(this.active && !this.paused) {
			var _g1 = 0;
			var _g = this.properties.start.length;
			while(_g1 < _g) {
				var i = _g1++;
				this.currentParameters[i] = Reflect.field(this.tweenProperties,"param" + i);
			}
			this.callMethod(this.target,this.currentParameters);
		}
	}
	,__class__: motion_actuators_MethodActuator
});
var motion_actuators_MotionPathActuator = function(target,duration,properties) {
	motion_actuators_SimpleActuator.call(this,target,duration,properties);
};
$hxClasses["motion.actuators.MotionPathActuator"] = motion_actuators_MotionPathActuator;
motion_actuators_MotionPathActuator.__name__ = true;
motion_actuators_MotionPathActuator.__super__ = motion_actuators_SimpleActuator;
motion_actuators_MotionPathActuator.prototype = $extend(motion_actuators_SimpleActuator.prototype,{
	apply: function() {
		var _g = 0;
		var _g1 = Reflect.fields(this.properties);
		while(_g < _g1.length) {
			var propertyName = _g1[_g];
			++_g;
			if(Object.prototype.hasOwnProperty.call(this.target,propertyName)) Reflect.setField(this.target,propertyName,(js_Boot.__cast(Reflect.field(this.properties,propertyName) , motion_IComponentPath)).get_end()); else Reflect.setProperty(this.target,propertyName,(js_Boot.__cast(Reflect.field(this.properties,propertyName) , motion_IComponentPath)).get_end());
		}
	}
	,initialize: function() {
		var details;
		var path;
		var _g = 0;
		var _g1 = Reflect.fields(this.properties);
		while(_g < _g1.length) {
			var propertyName = _g1[_g];
			++_g;
			path = js_Boot.__cast(Reflect.field(this.properties,propertyName) , motion_IComponentPath);
			if(path != null) {
				var isField = true;
				if(Object.prototype.hasOwnProperty.call(this.target,propertyName)) path.start = Reflect.field(this.target,propertyName); else {
					isField = false;
					path.start = Reflect.getProperty(this.target,propertyName);
				}
				details = new motion_actuators_PropertyPathDetails(this.target,propertyName,path,isField);
				this.propertyDetails.push(details);
			}
		}
		this.detailsLength = this.propertyDetails.length;
		this.initialized = true;
	}
	,update: function(currentTime) {
		if(!this.paused) {
			var details;
			var easing;
			var tweenPosition = (currentTime - this.timeOffset) / this.duration;
			if(tweenPosition > 1) tweenPosition = 1;
			if(!this.initialized) this.initialize();
			if(!this.special) {
				easing = this._ease.calculate(tweenPosition);
				var _g = 0;
				var _g1 = this.propertyDetails;
				while(_g < _g1.length) {
					var details1 = _g1[_g];
					++_g;
					if(details1.isField) Reflect.setField(details1.target,details1.propertyName,(js_Boot.__cast(details1 , motion_actuators_PropertyPathDetails)).path.calculate(easing)); else Reflect.setProperty(details1.target,details1.propertyName,(js_Boot.__cast(details1 , motion_actuators_PropertyPathDetails)).path.calculate(easing));
				}
			} else {
				if(!this._reverse) easing = this._ease.calculate(tweenPosition); else easing = this._ease.calculate(1 - tweenPosition);
				var endValue;
				var _g2 = 0;
				var _g11 = this.propertyDetails;
				while(_g2 < _g11.length) {
					var details2 = _g11[_g2];
					++_g2;
					if(!this._snapping) {
						if(details2.isField) Reflect.setField(details2.target,details2.propertyName,(js_Boot.__cast(details2 , motion_actuators_PropertyPathDetails)).path.calculate(easing)); else Reflect.setProperty(details2.target,details2.propertyName,(js_Boot.__cast(details2 , motion_actuators_PropertyPathDetails)).path.calculate(easing));
					} else if(details2.isField) Reflect.setField(details2.target,details2.propertyName,Math.round((js_Boot.__cast(details2 , motion_actuators_PropertyPathDetails)).path.calculate(easing))); else Reflect.setProperty(details2.target,details2.propertyName,Math.round((js_Boot.__cast(details2 , motion_actuators_PropertyPathDetails)).path.calculate(easing)));
				}
			}
			if(tweenPosition == 1) {
				if(this._repeat == 0) {
					this.active = false;
					if(this.toggleVisible && this.getField(this.target,"alpha") == 0) this.setField_motion_actuators_MotionPathActuator_T(this.target,"visible",false);
					this.complete(true);
					return;
				} else {
					if(this._onRepeat != null) this.callMethod(this._onRepeat,this._onRepeatParams);
					if(this._reflect) this._reverse = !this._reverse;
					this.startTime = currentTime;
					this.timeOffset = this.startTime + this._delay;
					if(this._repeat > 0) this._repeat--;
				}
			}
			if(this.sendChange) this.change();
		}
	}
	,__class__: motion_actuators_MotionPathActuator
});
var motion_actuators_PropertyDetails = function(target,propertyName,start,change,isField) {
	if(isField == null) isField = true;
	this.target = target;
	this.propertyName = propertyName;
	this.start = start;
	this.change = change;
	this.isField = isField;
};
$hxClasses["motion.actuators.PropertyDetails"] = motion_actuators_PropertyDetails;
motion_actuators_PropertyDetails.__name__ = true;
motion_actuators_PropertyDetails.prototype = {
	__class__: motion_actuators_PropertyDetails
};
var motion_actuators_PropertyPathDetails = function(target,propertyName,path,isField) {
	if(isField == null) isField = true;
	motion_actuators_PropertyDetails.call(this,target,propertyName,0,0,isField);
	this.path = path;
};
$hxClasses["motion.actuators.PropertyPathDetails"] = motion_actuators_PropertyPathDetails;
motion_actuators_PropertyPathDetails.__name__ = true;
motion_actuators_PropertyPathDetails.__super__ = motion_actuators_PropertyDetails;
motion_actuators_PropertyPathDetails.prototype = $extend(motion_actuators_PropertyDetails.prototype,{
	__class__: motion_actuators_PropertyPathDetails
});
var motion_actuators_TransformActuator = function(target,duration,properties) {
	motion_actuators_SimpleActuator.call(this,target,duration,properties);
};
$hxClasses["motion.actuators.TransformActuator"] = motion_actuators_TransformActuator;
motion_actuators_TransformActuator.__name__ = true;
motion_actuators_TransformActuator.__super__ = motion_actuators_SimpleActuator;
motion_actuators_TransformActuator.prototype = $extend(motion_actuators_SimpleActuator.prototype,{
	apply: function() {
		this.initialize();
		if(this.endColorTransform != null) {
			var transform = this.getField(this.target,"transform");
			this.setField_openfl_geom_Transform(transform,"colorTransform",this.endColorTransform);
		}
		if(this.endSoundTransform != null) this.setField_motion_actuators_TransformActuator_T(this.target,"soundTransform",this.endSoundTransform);
	}
	,initialize: function() {
		if(Object.prototype.hasOwnProperty.call(this.properties,"colorValue") && js_Boot.__instanceof(this.target,openfl_display_DisplayObject)) this.initializeColor();
		if(Object.prototype.hasOwnProperty.call(this.properties,"soundVolume") || Object.prototype.hasOwnProperty.call(this.properties,"soundPan")) this.initializeSound();
		this.detailsLength = this.propertyDetails.length;
		this.initialized = true;
	}
	,initializeColor: function() {
		this.endColorTransform = new openfl_geom_ColorTransform();
		var color = this.properties.colorValue;
		var strength = this.properties.colorStrength;
		if(strength < 1) {
			var multiplier;
			var offset;
			if(strength < 0.5) {
				multiplier = 1;
				offset = strength * 2;
			} else {
				multiplier = 1 - (strength - 0.5) * 2;
				offset = 1;
			}
			this.endColorTransform.redMultiplier = multiplier;
			this.endColorTransform.greenMultiplier = multiplier;
			this.endColorTransform.blueMultiplier = multiplier;
			this.endColorTransform.redOffset = offset * (color >> 16 & 255);
			this.endColorTransform.greenOffset = offset * (color >> 8 & 255);
			this.endColorTransform.blueOffset = offset * (color & 255);
		} else {
			this.endColorTransform.redMultiplier = 0;
			this.endColorTransform.greenMultiplier = 0;
			this.endColorTransform.blueMultiplier = 0;
			this.endColorTransform.redOffset = color >> 16 & 255;
			this.endColorTransform.greenOffset = color >> 8 & 255;
			this.endColorTransform.blueOffset = color & 255;
		}
		var propertyNames = ["redMultiplier","greenMultiplier","blueMultiplier","redOffset","greenOffset","blueOffset"];
		if(Object.prototype.hasOwnProperty.call(this.properties,"colorAlpha")) {
			this.endColorTransform.alphaMultiplier = this.properties.colorAlpha;
			propertyNames.push("alphaMultiplier");
		} else this.endColorTransform.alphaMultiplier = this.getField(this.target,"alpha");
		var transform = this.getField(this.target,"transform");
		var begin = this.getField(transform,"colorTransform");
		this.tweenColorTransform = new openfl_geom_ColorTransform();
		var details;
		var start;
		var _g = 0;
		while(_g < propertyNames.length) {
			var propertyName = propertyNames[_g];
			++_g;
			start = this.getField(begin,propertyName);
			details = new motion_actuators_PropertyDetails(this.tweenColorTransform,propertyName,start,this.getField(this.endColorTransform,propertyName) - start);
			this.propertyDetails.push(details);
		}
	}
	,initializeSound: function() {
		if(this.getField(this.target,"soundTransform") == null) this.setField_motion_actuators_TransformActuator_T(this.target,"soundTransform",new openfl_media_SoundTransform());
		var start = this.getField(this.target,"soundTransform");
		this.endSoundTransform = this.getField(this.target,"soundTransform");
		this.tweenSoundTransform = new openfl_media_SoundTransform();
		if(Object.prototype.hasOwnProperty.call(this.properties,"soundVolume")) {
			this.endSoundTransform.volume = this.properties.soundVolume;
			this.propertyDetails.push(new motion_actuators_PropertyDetails(this.tweenSoundTransform,"volume",start.volume,this.endSoundTransform.volume - start.volume));
		}
		if(Object.prototype.hasOwnProperty.call(this.properties,"soundPan")) {
			this.endSoundTransform.pan = this.properties.soundPan;
			this.propertyDetails.push(new motion_actuators_PropertyDetails(this.tweenSoundTransform,"pan",start.pan,this.endSoundTransform.pan - start.pan));
		}
	}
	,update: function(currentTime) {
		motion_actuators_SimpleActuator.prototype.update.call(this,currentTime);
		if(this.endColorTransform != null) {
			var transform = this.getField(this.target,"transform");
			this.setField_openfl_geom_Transform(transform,"colorTransform",this.tweenColorTransform);
		}
		if(this.endSoundTransform != null) this.setField_motion_actuators_TransformActuator_T(this.target,"soundTransform",this.tweenSoundTransform);
	}
	,__class__: motion_actuators_TransformActuator
});
var openfl_IAssetCache = function() { };
$hxClasses["openfl.IAssetCache"] = openfl_IAssetCache;
openfl_IAssetCache.__name__ = true;
openfl_IAssetCache.prototype = {
	__class__: openfl_IAssetCache
	,__properties__: {get_enabled:"get_enabled"}
};
var openfl_AssetCache = function() {
	this.__enabled = true;
	this.bitmapData = new haxe_ds_StringMap();
	this.font = new haxe_ds_StringMap();
	this.sound = new haxe_ds_StringMap();
};
$hxClasses["openfl.AssetCache"] = openfl_AssetCache;
openfl_AssetCache.__name__ = true;
openfl_AssetCache.__interfaces__ = [openfl_IAssetCache];
openfl_AssetCache.prototype = {
	getBitmapData: function(id) {
		return this.bitmapData.get(id);
	}
	,getFont: function(id) {
		return this.font.get(id);
	}
	,hasBitmapData: function(id) {
		return this.bitmapData.exists(id);
	}
	,hasFont: function(id) {
		return this.font.exists(id);
	}
	,setBitmapData: function(id,bitmapData) {
		this.bitmapData.set(id,bitmapData);
	}
	,setFont: function(id,font) {
		this.font.set(id,font);
	}
	,get_enabled: function() {
		return this.__enabled;
	}
	,__class__: openfl_AssetCache
	,__properties__: {get_enabled:"get_enabled"}
};
var openfl_Assets = function() { };
$hxClasses["openfl.Assets"] = openfl_Assets;
openfl_Assets.__name__ = true;
openfl_Assets.getBitmapData = function(id,useCache) {
	if(useCache == null) useCache = true;
	if(useCache && openfl_Assets.cache.get_enabled() && openfl_Assets.cache.hasBitmapData(id)) {
		var bitmapData = openfl_Assets.cache.getBitmapData(id);
		if(openfl_Assets.isValidBitmapData(bitmapData)) return bitmapData;
	}
	var image = lime_Assets.getImage(id,false);
	if(image != null) {
		var bitmapData1 = openfl_display_BitmapData.fromImage(image);
		if(useCache && openfl_Assets.cache.get_enabled()) openfl_Assets.cache.setBitmapData(id,bitmapData1);
		return bitmapData1;
	}
	return null;
};
openfl_Assets.getFont = function(id,useCache) {
	if(useCache == null) useCache = true;
	if(useCache && openfl_Assets.cache.get_enabled() && openfl_Assets.cache.hasFont(id)) return openfl_Assets.cache.getFont(id);
	var limeFont = lime_Assets.getFont(id,false);
	if(limeFont != null) {
		var font = openfl_text_Font.__fromLimeFont(limeFont);
		if(useCache && openfl_Assets.cache.get_enabled()) openfl_Assets.cache.setFont(id,font);
		return font;
	}
	return new openfl_text_Font();
};
openfl_Assets.isValidBitmapData = function(bitmapData) {
	return bitmapData != null && bitmapData.image != null;
};
var openfl_display_MovieClip = function() {
	openfl_display_Sprite.call(this);
	this.__currentFrame = 0;
	this.__currentLabels = [];
	this.__totalFrames = 0;
	this.enabled = true;
};
$hxClasses["openfl.display.MovieClip"] = openfl_display_MovieClip;
openfl_display_MovieClip.__name__ = true;
openfl_display_MovieClip.__super__ = openfl_display_Sprite;
openfl_display_MovieClip.prototype = $extend(openfl_display_Sprite.prototype,{
	__class__: openfl_display_MovieClip
});
var openfl_display_LoaderInfo = function() {
	openfl_events_EventDispatcher.call(this);
	this.applicationDomain = openfl_system_ApplicationDomain.currentDomain;
	this.bytesLoaded = 0;
	this.bytesTotal = 0;
	this.childAllowsParent = true;
	this.parameters = { };
};
$hxClasses["openfl.display.LoaderInfo"] = openfl_display_LoaderInfo;
openfl_display_LoaderInfo.__name__ = true;
openfl_display_LoaderInfo.create = function(loader) {
	var loaderInfo = new openfl_display_LoaderInfo();
	loaderInfo.uncaughtErrorEvents = new openfl_events_UncaughtErrorEvents();
	if(loader != null) loaderInfo.loader = loader; else loaderInfo.url = openfl_display_LoaderInfo.__rootURL;
	return loaderInfo;
};
openfl_display_LoaderInfo.__super__ = openfl_events_EventDispatcher;
openfl_display_LoaderInfo.prototype = $extend(openfl_events_EventDispatcher.prototype,{
	__class__: openfl_display_LoaderInfo
});
var openfl_system_ApplicationDomain = function(parentDomain) {
	if(parentDomain != null) this.parentDomain = parentDomain; else this.parentDomain = openfl_system_ApplicationDomain.currentDomain;
};
$hxClasses["openfl.system.ApplicationDomain"] = openfl_system_ApplicationDomain;
openfl_system_ApplicationDomain.__name__ = true;
openfl_system_ApplicationDomain.prototype = {
	__class__: openfl_system_ApplicationDomain
};
var openfl_events_UncaughtErrorEvents = function(target) {
	openfl_events_EventDispatcher.call(this,target);
};
$hxClasses["openfl.events.UncaughtErrorEvents"] = openfl_events_UncaughtErrorEvents;
openfl_events_UncaughtErrorEvents.__name__ = true;
openfl_events_UncaughtErrorEvents.__super__ = openfl_events_EventDispatcher;
openfl_events_UncaughtErrorEvents.prototype = $extend(openfl_events_EventDispatcher.prototype,{
	__class__: openfl_events_UncaughtErrorEvents
});
var openfl_geom_Matrix = function(a,b,c,d,tx,ty) {
	if(ty == null) ty = 0;
	if(tx == null) tx = 0;
	if(d == null) d = 1;
	if(c == null) c = 0;
	if(b == null) b = 0;
	if(a == null) a = 1;
	this.a = a;
	this.b = b;
	this.c = c;
	this.d = d;
	this.tx = tx;
	this.ty = ty;
};
$hxClasses["openfl.geom.Matrix"] = openfl_geom_Matrix;
openfl_geom_Matrix.__name__ = true;
openfl_geom_Matrix.prototype = {
	clone: function() {
		return new openfl_geom_Matrix(this.a,this.b,this.c,this.d,this.tx,this.ty);
	}
	,concat: function(m) {
		var a1 = this.a * m.a + this.b * m.c;
		this.b = this.a * m.b + this.b * m.d;
		this.a = a1;
		var c1 = this.c * m.a + this.d * m.c;
		this.d = this.c * m.b + this.d * m.d;
		this.c = c1;
		var tx1 = this.tx * m.a + this.ty * m.c + m.tx;
		this.ty = this.tx * m.b + this.ty * m.d + m.ty;
		this.tx = tx1;
	}
	,copyFrom: function(sourceMatrix) {
		this.a = sourceMatrix.a;
		this.b = sourceMatrix.b;
		this.c = sourceMatrix.c;
		this.d = sourceMatrix.d;
		this.tx = sourceMatrix.tx;
		this.ty = sourceMatrix.ty;
	}
	,deltaTransformPoint: function(point) {
		return new openfl_geom_Point(point.x * this.a + point.y * this.c,point.x * this.b + point.y * this.d);
	}
	,identity: function() {
		this.a = 1;
		this.b = 0;
		this.c = 0;
		this.d = 1;
		this.tx = 0;
		this.ty = 0;
	}
	,invert: function() {
		var norm = this.a * this.d - this.b * this.c;
		if(norm == 0) {
			this.a = this.b = this.c = this.d = 0;
			this.tx = -this.tx;
			this.ty = -this.ty;
		} else {
			norm = 1.0 / norm;
			var a1 = this.d * norm;
			this.d = this.a * norm;
			this.a = a1;
			this.b *= -norm;
			this.c *= -norm;
			var tx1 = -this.a * this.tx - this.c * this.ty;
			this.ty = -this.b * this.tx - this.d * this.ty;
			this.tx = tx1;
		}
		return this;
	}
	,scale: function(sx,sy) {
		this.a *= sx;
		this.b *= sy;
		this.c *= sx;
		this.d *= sy;
		this.tx *= sx;
		this.ty *= sy;
	}
	,to3DString: function(roundPixels) {
		if(roundPixels == null) roundPixels = false;
		if(roundPixels) return "matrix3d(" + this.a + ", " + this.b + ", " + "0, 0, " + this.c + ", " + this.d + ", " + "0, 0, 0, 0, 1, 0, " + (this.tx | 0) + ", " + (this.ty | 0) + ", 0, 1)"; else return "matrix3d(" + this.a + ", " + this.b + ", " + "0, 0, " + this.c + ", " + this.d + ", " + "0, 0, 0, 0, 1, 0, " + this.tx + ", " + this.ty + ", 0, 1)";
	}
	,transformPoint: function(pos) {
		return new openfl_geom_Point(pos.x * this.a + pos.y * this.c + this.tx,pos.x * this.b + pos.y * this.d + this.ty);
	}
	,translate: function(dx,dy) {
		this.tx += dx;
		this.ty += dy;
	}
	,toArray: function(transpose) {
		if(transpose == null) transpose = false;
		if(this.__array == null) {
			var this1;
			this1 = new Float32Array(9);
			this.__array = this1;
		}
		if(transpose) {
			this.__array[0] = this.a;
			this.__array[1] = this.b;
			this.__array[2] = 0;
			this.__array[3] = this.c;
			this.__array[4] = this.d;
			this.__array[5] = 0;
			this.__array[6] = this.tx;
			this.__array[7] = this.ty;
			this.__array[8] = 1;
		} else {
			this.__array[0] = this.a;
			this.__array[1] = this.c;
			this.__array[2] = this.tx;
			this.__array[3] = this.b;
			this.__array[4] = this.d;
			this.__array[5] = this.ty;
			this.__array[6] = 0;
			this.__array[7] = 0;
			this.__array[8] = 1;
		}
		return this.__array;
	}
	,__toMatrix3: function() {
		return new lime_math_Matrix3(this.a,this.b,this.c,this.d,this.tx,this.ty);
	}
	,__transformInversePoint: function(point) {
		var norm = this.a * this.d - this.b * this.c;
		if(norm == 0) {
			point.x = -this.tx;
			point.y = -this.ty;
		} else {
			var px = 1.0 / norm * (this.c * (this.ty - point.y) + this.d * (point.x - this.tx));
			point.y = 1.0 / norm * (this.a * (point.y - this.ty) + this.b * (this.tx - point.x));
			point.x = px;
		}
	}
	,__transformInverseX: function(px,py) {
		var norm = this.a * this.d - this.b * this.c;
		if(norm == 0) return -this.tx; else return 1.0 / norm * (this.c * (this.ty - py) + this.d * (px - this.tx));
	}
	,__transformInverseY: function(px,py) {
		var norm = this.a * this.d - this.b * this.c;
		if(norm == 0) return -this.ty; else return 1.0 / norm * (this.a * (py - this.ty) + this.b * (this.tx - px));
	}
	,__translateTransformed: function(px,py) {
		this.tx = px * this.a + py * this.c + this.tx;
		this.ty = px * this.b + py * this.d + this.ty;
	}
	,__class__: openfl_geom_Matrix
};
var openfl_geom_ColorTransform = function(redMultiplier,greenMultiplier,blueMultiplier,alphaMultiplier,redOffset,greenOffset,blueOffset,alphaOffset) {
	if(alphaOffset == null) alphaOffset = 0;
	if(blueOffset == null) blueOffset = 0;
	if(greenOffset == null) greenOffset = 0;
	if(redOffset == null) redOffset = 0;
	if(alphaMultiplier == null) alphaMultiplier = 1;
	if(blueMultiplier == null) blueMultiplier = 1;
	if(greenMultiplier == null) greenMultiplier = 1;
	if(redMultiplier == null) redMultiplier = 1;
	this.redMultiplier = redMultiplier;
	this.greenMultiplier = greenMultiplier;
	this.blueMultiplier = blueMultiplier;
	this.alphaMultiplier = alphaMultiplier;
	this.redOffset = redOffset;
	this.greenOffset = greenOffset;
	this.blueOffset = blueOffset;
	this.alphaOffset = alphaOffset;
};
$hxClasses["openfl.geom.ColorTransform"] = openfl_geom_ColorTransform;
openfl_geom_ColorTransform.__name__ = true;
openfl_geom_ColorTransform.prototype = {
	__clone: function() {
		return new openfl_geom_ColorTransform(this.redMultiplier,this.greenMultiplier,this.blueMultiplier,this.alphaMultiplier,this.redOffset,this.greenOffset,this.blueOffset,this.alphaOffset);
	}
	,__combine: function(ct) {
		this.redMultiplier *= ct.redMultiplier;
		this.greenMultiplier *= ct.greenMultiplier;
		this.blueMultiplier *= ct.blueMultiplier;
		this.alphaMultiplier *= ct.alphaMultiplier;
		this.redOffset += ct.redOffset;
		this.greenOffset += ct.greenOffset;
		this.blueOffset += ct.blueOffset;
		this.alphaOffset += ct.alphaOffset;
	}
	,__equals: function(ct,skipAlphaMultiplier) {
		if(skipAlphaMultiplier == null) skipAlphaMultiplier = false;
		return ct != null && this.redMultiplier == ct.redMultiplier && this.greenMultiplier == ct.greenMultiplier && this.blueMultiplier == ct.blueMultiplier && (skipAlphaMultiplier || this.alphaMultiplier == ct.alphaMultiplier) && this.redOffset == ct.redOffset && this.greenOffset == ct.greenOffset && this.blueOffset == ct.blueOffset && this.alphaOffset == ct.alphaOffset;
	}
	,__isDefault: function() {
		return this.redMultiplier == 1 && this.greenMultiplier == 1 && this.blueMultiplier == 1 && this.alphaMultiplier == 1 && this.redOffset == 0 && this.greenOffset == 0 && this.blueOffset == 0 && this.alphaOffset == 0;
	}
	,__toLimeColorMatrix: function() {
		return (function($this) {
			var $r;
			var array = [$this.redMultiplier,0,0,0,$this.redOffset / 255,0,$this.greenMultiplier,0,0,$this.greenOffset / 255,0,0,$this.blueMultiplier,0,$this.blueOffset / 255,0,0,0,$this.alphaMultiplier,$this.alphaOffset / 255];
			var this1;
			if(array != null) this1 = new Float32Array(array); else this1 = null;
			$r = this1;
			return $r;
		}(this));
	}
	,__class__: openfl_geom_ColorTransform
};
var openfl_Lib = function() { };
$hxClasses["openfl.Lib"] = openfl_Lib;
openfl_Lib.__name__ = true;
openfl_Lib.application = null;
openfl_Lib.embed = $hx_exports.openfl.embed = function(elementName,width,height,background,assetsPrefix) {
	lime_system_System.embed(elementName,width,height,background,assetsPrefix);
};
openfl_Lib.getTimer = function() {
	return lime_system_System.getTimer();
};
var openfl_VectorData = function() {
	this.length = 0;
};
$hxClasses["openfl.VectorData"] = openfl_VectorData;
openfl_VectorData.__name__ = true;
openfl_VectorData.prototype = {
	__class__: openfl_VectorData
};
var openfl__$internal_renderer_AbstractMaskManager = function(renderSession) {
	this.renderSession = renderSession;
};
$hxClasses["openfl._internal.renderer.AbstractMaskManager"] = openfl__$internal_renderer_AbstractMaskManager;
openfl__$internal_renderer_AbstractMaskManager.__name__ = true;
openfl__$internal_renderer_AbstractMaskManager.prototype = {
	pushMask: function(mask) {
	}
	,pushRect: function(rect,transform) {
	}
	,popMask: function() {
	}
	,popRect: function() {
	}
	,saveState: function() {
	}
	,restoreState: function() {
	}
	,__class__: openfl__$internal_renderer_AbstractMaskManager
};
var openfl__$internal_renderer_AbstractRenderer = function(width,height) {
	this.width = width;
	this.height = height;
};
$hxClasses["openfl._internal.renderer.AbstractRenderer"] = openfl__$internal_renderer_AbstractRenderer;
openfl__$internal_renderer_AbstractRenderer.__name__ = true;
openfl__$internal_renderer_AbstractRenderer.prototype = {
	render: function(stage) {
	}
	,setViewport: function(x,y,width,height) {
	}
	,resize: function(width,height) {
	}
	,__class__: openfl__$internal_renderer_AbstractRenderer
};
var openfl__$internal_renderer_DrawCommandBuffer = function() {
	this.types = [];
	this.b = [];
	this.i = [];
	this.f = [];
	this.o = [];
	this.ff = [];
	this.ii = [];
	this.ts = [];
};
$hxClasses["openfl._internal.renderer.DrawCommandBuffer"] = openfl__$internal_renderer_DrawCommandBuffer;
openfl__$internal_renderer_DrawCommandBuffer.__name__ = true;
openfl__$internal_renderer_DrawCommandBuffer.prototype = {
	append: function(other) {
		var data = new openfl__$internal_renderer_DrawCommandReader(other);
		var _g = 0;
		var _g1 = other.types;
		while(_g < _g1.length) {
			var type = _g1[_g];
			++_g;
			switch(type[1]) {
			case 0:
				var c;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL;
				c = data;
				this.beginBitmapFill(c.buffer.o[c.oPos],c.buffer.o[c.oPos + 1],c.buffer.b[c.bPos],c.buffer.b[c.bPos + 1]);
				break;
			case 1:
				var c1;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_FILL;
				c1 = data;
				this.beginFill(c1.buffer.i[c1.iPos],c1.buffer.f[c1.fPos]);
				break;
			case 2:
				var c2;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL;
				c2 = data;
				this.beginGradientFill(c2.buffer.o[c2.oPos],c2.buffer.ii[c2.iiPos],c2.buffer.ff[c2.ffPos],c2.buffer.ii[c2.iiPos + 1],c2.buffer.o[c2.oPos + 1],c2.buffer.o[c2.oPos + 2],c2.buffer.o[c2.oPos + 3],c2.buffer.o[c2.oPos + 4]);
				break;
			case 3:
				var c3;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO;
				c3 = data;
				this.cubicCurveTo(c3.buffer.f[c3.fPos],c3.buffer.f[c3.fPos + 1],c3.buffer.f[c3.fPos + 3],c3.buffer.f[c3.fPos + 4],c3.buffer.f[c3.fPos + 5],c3.buffer.f[c3.fPos + 6]);
				break;
			case 4:
				var c4;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CURVE_TO;
				c4 = data;
				this.curveTo(c4.buffer.f[c4.fPos],c4.buffer.f[c4.fPos + 1],c4.buffer.f[c4.fPos + 2],c4.buffer.f[c4.fPos + 3]);
				break;
			case 5:
				var c5;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE;
				c5 = data;
				this.drawCircle(c5.buffer.f[c5.fPos],c5.buffer.f[c5.fPos + 1],c5.buffer.f[c5.fPos + 2]);
				break;
			case 6:
				var c6;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE;
				c6 = data;
				this.drawEllipse(c6.buffer.f[c6.fPos],c6.buffer.f[c6.fPos + 1],c6.buffer.f[c6.fPos + 2],c6.buffer.f[c6.fPos + 3]);
				break;
			case 7:
				var c7;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_PATH;
				c7 = data;
				this.drawPath(c7.buffer.o[c7.oPos],c7.buffer.o[c7.oPos + 1],c7.buffer.o[c7.oPos + 2]);
				break;
			case 8:
				var c8;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_RECT;
				c8 = data;
				this.drawRect(c8.buffer.f[c8.fPos],c8.buffer.f[c8.fPos + 1],c8.buffer.f[c8.fPos + 2],c8.buffer.f[c8.fPos + 3]);
				break;
			case 9:
				var c9;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT;
				c9 = data;
				this.drawRoundRect(c9.buffer.f[c9.fPos],c9.buffer.f[c9.fPos + 1],c9.buffer.f[c9.fPos + 2],c9.buffer.f[c9.fPos + 3],c9.buffer.f[c9.fPos + 4],c9.buffer.f[c9.fPos + 5]);
				break;
			case 10:
				var c10;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_TILES;
				c10 = data;
				this.drawTiles(c10.buffer.ts[c10.tsPos],c10.buffer.ff[c10.ffPos],c10.buffer.b[c10.bPos],c10.buffer.i[c10.iPos],c10.buffer.o[c10.oPos],c10.buffer.i[c10.iPos + 1]);
				break;
			case 11:
				var c11;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_TRIANGLES;
				c11 = data;
				this.drawTriangles(c11.buffer.o[c11.oPos],c11.buffer.o[c11.oPos + 1],c11.buffer.o[c11.oPos + 2],c11.buffer.o[c11.oPos + 3],c11.buffer.o[c11.oPos + 4],c11.buffer.i[c11.iPos]);
				break;
			case 12:
				var c12;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.END_FILL;
				c12 = data;
				this.endFill();
				break;
			case 13:
				var c13;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_BITMAP_STYLE;
				c13 = data;
				this.lineBitmapStyle(c13.buffer.o[c13.oPos],c13.buffer.o[c13.oPos + 1],c13.buffer.b[c13.bPos],c13.buffer.b[c13.bPos + 1]);
				break;
			case 14:
				var c14;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_GRADIENT_STYLE;
				c14 = data;
				this.lineGradientStyle(c14.buffer.o[c14.oPos],c14.buffer.ii[c14.iiPos],c14.buffer.ff[c14.ffPos],c14.buffer.ii[c14.iiPos + 1],c14.buffer.o[c14.oPos + 1],c14.buffer.o[c14.oPos + 2],c14.buffer.o[c14.oPos + 3],c14.buffer.o[c14.oPos + 4]);
				break;
			case 15:
				var c15;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_STYLE;
				c15 = data;
				this.lineStyle(c15.buffer.o[c15.oPos],c15.buffer.o[c15.oPos + 1],c15.buffer.o[c15.oPos + 2],c15.buffer.o[c15.oPos + 3],c15.buffer.o[c15.oPos + 4],c15.buffer.o[c15.oPos + 5],c15.buffer.o[c15.oPos + 6],c15.buffer.o[c15.oPos + 7]);
				break;
			case 16:
				var c16;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_TO;
				c16 = data;
				this.lineTo(c16.buffer.f[c16.fPos],c16.buffer.f[c16.fPos + 1]);
				break;
			case 17:
				var c17;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.MOVE_TO;
				c17 = data;
				this.moveTo(c17.buffer.f[c17.fPos],c17.buffer.f[c17.fPos + 1]);
				break;
			case 18:
				var c18;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.OVERRIDE_MATRIX;
				c18 = data;
				this.overrideMatrix(c18.buffer.o[c18.oPos]);
				break;
			default:
			}
		}
		data.destroy();
		return other;
	}
	,beginBitmapFill: function(bitmap,matrix,repeat,smooth) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL);
		this.o.push(bitmap);
		this.o.push(matrix);
		this.b.push(repeat);
		this.b.push(smooth);
	}
	,beginFill: function(color,alpha) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.BEGIN_FILL);
		this.i.push(color);
		this.f.push(alpha);
	}
	,beginGradientFill: function(type,colors,alphas,ratios,matrix,spreadMethod,interpolationMethod,focalPointRatio) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL);
		this.o.push(type);
		this.ii.push(colors);
		this.ff.push(alphas);
		this.ii.push(ratios);
		this.o.push(matrix);
		this.o.push(spreadMethod);
		this.o.push(interpolationMethod);
		this.o.push(focalPointRatio);
	}
	,clear: function() {
		this.types.splice(0,this.types.length);
		this.b.splice(0,this.b.length);
		this.i.splice(0,this.i.length);
		this.f.splice(0,this.f.length);
		this.o.splice(0,this.o.length);
		this.ff.splice(0,this.ff.length);
		this.ii.splice(0,this.ii.length);
		this.ts.splice(0,this.ts.length);
	}
	,cubicCurveTo: function(controlX1,controlY1,controlX2,controlY2,anchorX,anchorY) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO);
		this.f.push(controlX1);
		this.f.push(controlY1);
		this.f.push(controlX2);
		this.f.push(controlY2);
		this.f.push(anchorX);
		this.f.push(anchorY);
	}
	,curveTo: function(controlX,controlY,anchorX,anchorY) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.CURVE_TO);
		this.f.push(controlX);
		this.f.push(controlY);
		this.f.push(anchorX);
		this.f.push(anchorY);
	}
	,drawCircle: function(x,y,radius) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE);
		this.f.push(x);
		this.f.push(y);
		this.f.push(radius);
	}
	,drawEllipse: function(x,y,width,height) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE);
		this.f.push(x);
		this.f.push(y);
		this.f.push(width);
		this.f.push(height);
	}
	,drawPath: function(commands,data,winding) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.DRAW_PATH);
		this.o.push(commands);
		this.o.push(data);
		this.o.push(winding);
	}
	,drawRect: function(x,y,width,height) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.DRAW_RECT);
		this.f.push(x);
		this.f.push(y);
		this.f.push(width);
		this.f.push(height);
	}
	,drawRoundRect: function(x,y,width,height,rx,ry) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT);
		this.f.push(x);
		this.f.push(y);
		this.f.push(width);
		this.f.push(height);
		this.f.push(rx);
		this.f.push(ry);
	}
	,drawTiles: function(sheet,tileData,smooth,flags,shader,count) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.DRAW_TILES);
		this.ts.push(sheet);
		this.ff.push(tileData);
		this.b.push(smooth);
		this.i.push(flags);
		this.o.push(shader);
		this.i.push(count);
	}
	,drawTriangles: function(vertices,indices,uvtData,culling,colors,blendMode) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.DRAW_TRIANGLES);
		this.o.push(vertices);
		this.o.push(indices);
		this.o.push(uvtData);
		this.o.push(culling);
		this.o.push(colors);
		this.i.push(blendMode);
	}
	,endFill: function() {
		this.types.push(openfl__$internal_renderer_DrawCommandType.END_FILL);
	}
	,lineBitmapStyle: function(bitmap,matrix,repeat,smooth) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.LINE_BITMAP_STYLE);
		this.o.push(bitmap);
		this.o.push(matrix);
		this.b.push(repeat);
		this.b.push(smooth);
	}
	,lineGradientStyle: function(type,colors,alphas,ratios,matrix,spreadMethod,interpolationMethod,focalPointRatio) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.LINE_GRADIENT_STYLE);
		this.o.push(type);
		this.ii.push(colors);
		this.ff.push(alphas);
		this.ii.push(ratios);
		this.o.push(matrix);
		this.o.push(spreadMethod);
		this.o.push(interpolationMethod);
		this.o.push(focalPointRatio);
	}
	,lineStyle: function(thickness,color,alpha,pixelHinting,scaleMode,caps,joints,miterLimit) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.LINE_STYLE);
		this.o.push(thickness);
		this.o.push(color);
		this.o.push(alpha);
		this.o.push(pixelHinting);
		this.o.push(scaleMode);
		this.o.push(caps);
		this.o.push(joints);
		this.o.push(miterLimit);
	}
	,lineTo: function(x,y) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.LINE_TO);
		this.f.push(x);
		this.f.push(y);
	}
	,moveTo: function(x,y) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.MOVE_TO);
		this.f.push(x);
		this.f.push(y);
	}
	,overrideMatrix: function(matrix) {
		this.types.push(openfl__$internal_renderer_DrawCommandType.OVERRIDE_MATRIX);
		this.o.push(matrix);
	}
	,get_length: function() {
		return this.types.length;
	}
	,__class__: openfl__$internal_renderer_DrawCommandBuffer
	,__properties__: {get_length:"get_length"}
};
var openfl__$internal_renderer_DrawCommandReader = function(buffer) {
	this.buffer = buffer;
	this.bPos = this.iPos = this.fPos = this.oPos = this.ffPos = this.iiPos = this.tsPos = 0;
	this.prev = openfl__$internal_renderer_DrawCommandType.UNKNOWN;
};
$hxClasses["openfl._internal.renderer.DrawCommandReader"] = openfl__$internal_renderer_DrawCommandReader;
openfl__$internal_renderer_DrawCommandReader.__name__ = true;
openfl__$internal_renderer_DrawCommandReader.prototype = {
	advance: function() {
		var _g = this.prev;
		switch(_g[1]) {
		case 0:
			this.oPos += 2;
			this.bPos += 2;
			break;
		case 1:
			this.iPos += 1;
			this.fPos += 1;
			break;
		case 2:
			this.oPos += 5;
			this.iiPos += 2;
			this.ffPos += 1;
			break;
		case 3:
			this.fPos += 6;
			break;
		case 4:
			this.fPos += 4;
			break;
		case 5:
			this.fPos += 3;
			break;
		case 6:
			this.fPos += 4;
			break;
		case 7:
			this.oPos += 3;
			break;
		case 8:
			this.fPos += 4;
			break;
		case 9:
			this.fPos += 6;
			break;
		case 10:
			this.tsPos += 1;
			this.ffPos += 1;
			this.bPos += 1;
			this.iPos += 2;
			this.oPos += 1;
			break;
		case 11:
			this.oPos += 5;
			this.iPos += 1;
			break;
		case 12:
			break;
		case 13:
			this.oPos += 2;
			this.bPos += 2;
			break;
		case 14:
			this.oPos += 5;
			this.iiPos += 2;
			this.ffPos += 1;
			break;
		case 15:
			this.oPos += 8;
			break;
		case 16:
			this.fPos += 2;
			break;
		case 17:
			this.fPos += 2;
			break;
		case 18:
			this.oPos += 1;
			break;
		default:
		}
	}
	,destroy: function() {
		this.buffer = null;
		this.reset();
	}
	,reset: function() {
		this.bPos = this.iPos = this.fPos = this.oPos = this.ffPos = this.iiPos = this.tsPos = 0;
	}
	,__class__: openfl__$internal_renderer_DrawCommandReader
};
var openfl__$internal_renderer_DrawCommandType = $hxClasses["openfl._internal.renderer.DrawCommandType"] = { __ename__ : true, __constructs__ : ["BEGIN_BITMAP_FILL","BEGIN_FILL","BEGIN_GRADIENT_FILL","CUBIC_CURVE_TO","CURVE_TO","DRAW_CIRCLE","DRAW_ELLIPSE","DRAW_PATH","DRAW_RECT","DRAW_ROUND_RECT","DRAW_TILES","DRAW_TRIANGLES","END_FILL","LINE_BITMAP_STYLE","LINE_GRADIENT_STYLE","LINE_STYLE","LINE_TO","MOVE_TO","OVERRIDE_MATRIX","UNKNOWN"] };
openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL = ["BEGIN_BITMAP_FILL",0];
openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL.toString = $estr;
openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.BEGIN_FILL = ["BEGIN_FILL",1];
openfl__$internal_renderer_DrawCommandType.BEGIN_FILL.toString = $estr;
openfl__$internal_renderer_DrawCommandType.BEGIN_FILL.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL = ["BEGIN_GRADIENT_FILL",2];
openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL.toString = $estr;
openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO = ["CUBIC_CURVE_TO",3];
openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO.toString = $estr;
openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.CURVE_TO = ["CURVE_TO",4];
openfl__$internal_renderer_DrawCommandType.CURVE_TO.toString = $estr;
openfl__$internal_renderer_DrawCommandType.CURVE_TO.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE = ["DRAW_CIRCLE",5];
openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE.toString = $estr;
openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE = ["DRAW_ELLIPSE",6];
openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE.toString = $estr;
openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.DRAW_PATH = ["DRAW_PATH",7];
openfl__$internal_renderer_DrawCommandType.DRAW_PATH.toString = $estr;
openfl__$internal_renderer_DrawCommandType.DRAW_PATH.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.DRAW_RECT = ["DRAW_RECT",8];
openfl__$internal_renderer_DrawCommandType.DRAW_RECT.toString = $estr;
openfl__$internal_renderer_DrawCommandType.DRAW_RECT.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT = ["DRAW_ROUND_RECT",9];
openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT.toString = $estr;
openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.DRAW_TILES = ["DRAW_TILES",10];
openfl__$internal_renderer_DrawCommandType.DRAW_TILES.toString = $estr;
openfl__$internal_renderer_DrawCommandType.DRAW_TILES.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.DRAW_TRIANGLES = ["DRAW_TRIANGLES",11];
openfl__$internal_renderer_DrawCommandType.DRAW_TRIANGLES.toString = $estr;
openfl__$internal_renderer_DrawCommandType.DRAW_TRIANGLES.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.END_FILL = ["END_FILL",12];
openfl__$internal_renderer_DrawCommandType.END_FILL.toString = $estr;
openfl__$internal_renderer_DrawCommandType.END_FILL.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.LINE_BITMAP_STYLE = ["LINE_BITMAP_STYLE",13];
openfl__$internal_renderer_DrawCommandType.LINE_BITMAP_STYLE.toString = $estr;
openfl__$internal_renderer_DrawCommandType.LINE_BITMAP_STYLE.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.LINE_GRADIENT_STYLE = ["LINE_GRADIENT_STYLE",14];
openfl__$internal_renderer_DrawCommandType.LINE_GRADIENT_STYLE.toString = $estr;
openfl__$internal_renderer_DrawCommandType.LINE_GRADIENT_STYLE.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.LINE_STYLE = ["LINE_STYLE",15];
openfl__$internal_renderer_DrawCommandType.LINE_STYLE.toString = $estr;
openfl__$internal_renderer_DrawCommandType.LINE_STYLE.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.LINE_TO = ["LINE_TO",16];
openfl__$internal_renderer_DrawCommandType.LINE_TO.toString = $estr;
openfl__$internal_renderer_DrawCommandType.LINE_TO.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.MOVE_TO = ["MOVE_TO",17];
openfl__$internal_renderer_DrawCommandType.MOVE_TO.toString = $estr;
openfl__$internal_renderer_DrawCommandType.MOVE_TO.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.OVERRIDE_MATRIX = ["OVERRIDE_MATRIX",18];
openfl__$internal_renderer_DrawCommandType.OVERRIDE_MATRIX.toString = $estr;
openfl__$internal_renderer_DrawCommandType.OVERRIDE_MATRIX.__enum__ = openfl__$internal_renderer_DrawCommandType;
openfl__$internal_renderer_DrawCommandType.UNKNOWN = ["UNKNOWN",19];
openfl__$internal_renderer_DrawCommandType.UNKNOWN.toString = $estr;
openfl__$internal_renderer_DrawCommandType.UNKNOWN.__enum__ = openfl__$internal_renderer_DrawCommandType;
var openfl__$internal_renderer_GraphicsPaths = function() { };
$hxClasses["openfl._internal.renderer.GraphicsPaths"] = openfl__$internal_renderer_GraphicsPaths;
openfl__$internal_renderer_GraphicsPaths.__name__ = true;
openfl__$internal_renderer_GraphicsPaths.ellipse = function(points,x,y,rx,ry,segmentCount) {
	var seg = Math.PI * 2 / segmentCount;
	var _g1 = 0;
	var _g = segmentCount + 1;
	while(_g1 < _g) {
		var i = _g1++;
		points.push(x + Math.sin(seg * i) * rx);
		points.push(y + Math.cos(seg * i) * ry);
	}
};
openfl__$internal_renderer_GraphicsPaths.cubicCurveTo = function(points,cx,cy,cx2,cy2,x,y) {
	var n = 20;
	var dt = 0;
	var dt2 = 0;
	var dt3 = 0;
	var t2 = 0;
	var t3 = 0;
	var fromX = points[points.length - 2];
	var fromY = points[points.length - 1];
	var px = 0;
	var py = 0;
	var tmp = 0;
	var _g1 = 1;
	var _g = n + 1;
	while(_g1 < _g) {
		var i = _g1++;
		tmp = i / n;
		dt = 1 - tmp;
		dt2 = dt * dt;
		dt3 = dt2 * dt;
		t2 = tmp * tmp;
		t3 = t2 * tmp;
		px = dt3 * fromX + 3 * dt2 * tmp * cx + 3 * dt * t2 * cx2 + t3 * x;
		py = dt3 * fromY + 3 * dt2 * tmp * cy + 3 * dt * t2 * cy2 + t3 * y;
		points.push(px);
		points.push(py);
	}
};
openfl__$internal_renderer_GraphicsPaths.curveTo = function(points,cx,cy,x,y) {
	var xa = 0;
	var ya = 0;
	var n = 20;
	var fromX = points[points.length - 2];
	var fromY = points[points.length - 1];
	var px = 0;
	var py = 0;
	var tmp = 0;
	var _g1 = 1;
	var _g = n + 1;
	while(_g1 < _g) {
		var i = _g1++;
		tmp = i / n;
		xa = fromX + (cx - fromX) * tmp;
		ya = fromY + (cy - fromY) * tmp;
		px = xa + (cx + (x - cx) * tmp - xa) * tmp;
		py = ya + (cy + (y - cy) * tmp - ya) * tmp;
		points.push(px);
		points.push(py);
	}
};
openfl__$internal_renderer_GraphicsPaths.roundRectangle = function(points,x,y,width,height,rx,ry) {
	var xe = x + width;
	var ye = y + height;
	var cx1 = -rx + rx * openfl__$internal_renderer_GraphicsPaths.SIN45;
	var cx2 = -rx + rx * openfl__$internal_renderer_GraphicsPaths.TAN22;
	var cy1 = -ry + ry * openfl__$internal_renderer_GraphicsPaths.SIN45;
	var cy2 = -ry + ry * openfl__$internal_renderer_GraphicsPaths.TAN22;
	points.push(xe);
	points.push(ye - ry);
	openfl__$internal_renderer_GraphicsPaths.curveTo(points,xe,ye + cy2,xe + cx1,ye + cy1);
	openfl__$internal_renderer_GraphicsPaths.curveTo(points,xe + cx2,ye,xe - rx,ye);
	points.push(x + rx);
	points.push(ye);
	openfl__$internal_renderer_GraphicsPaths.curveTo(points,x - cx2,ye,x - cx1,ye + cy1);
	openfl__$internal_renderer_GraphicsPaths.curveTo(points,x,ye + cy2,x,ye - ry);
	points.push(x);
	points.push(y + ry);
	openfl__$internal_renderer_GraphicsPaths.curveTo(points,x,y - cy2,x - cx1,y - cy1);
	openfl__$internal_renderer_GraphicsPaths.curveTo(points,x - cx2,y,x + rx,y);
	points.push(xe - rx);
	points.push(y);
	openfl__$internal_renderer_GraphicsPaths.curveTo(points,xe + cx2,y,xe + cx1,y - cy1);
	openfl__$internal_renderer_GraphicsPaths.curveTo(points,xe,y - cy2,xe,y + ry);
	points.push(xe);
	points.push(ye - ry);
};
var openfl__$internal_renderer_PolyK = function() { };
$hxClasses["openfl._internal.renderer.PolyK"] = openfl__$internal_renderer_PolyK;
openfl__$internal_renderer_PolyK.__name__ = true;
openfl__$internal_renderer_PolyK.triangulate = function(tgs,p) {
	var sign = true;
	var n = p.length >> 1;
	if(n < 3) return [];
	var avl;
	var _g = [];
	var _g1 = 0;
	while(_g1 < n) {
		var i1 = _g1++;
		_g.push(i1);
	}
	avl = _g;
	var i = 0;
	var al = n;
	var earFound = false;
	while(al > 3) {
		var i0 = avl[i % al];
		var i11 = avl[(i + 1) % al];
		var i2 = avl[(i + 2) % al];
		var ax = p[2 * i0];
		var ay = p[2 * i0 + 1];
		var bx = p[2 * i11];
		var by = p[2 * i11 + 1];
		var cx = p[2 * i2];
		var cy = p[2 * i2 + 1];
		earFound = false;
		if(openfl__$internal_renderer_PolyK._convex(ax,ay,bx,by,cx,cy,sign)) {
			earFound = true;
			var _g11 = 0;
			while(_g11 < al) {
				var j = _g11++;
				var vi = avl[j];
				if(vi == i0 || vi == i11 || vi == i2) continue;
				if(openfl__$internal_renderer_PolyK._PointInTriangle(p[2 * vi],p[2 * vi + 1],ax,ay,bx,by,cx,cy)) {
					earFound = false;
					break;
				}
			}
		}
		if(earFound) {
			tgs.push(i0);
			tgs.push(i11);
			tgs.push(i2);
			avl.splice((i + 1) % al,1);
			al--;
			i = 0;
		} else if(i++ > 3 * al) {
			if(sign) {
				tgs = [];
				var _g12 = [];
				var _g2 = 0;
				while(_g2 < n) {
					var k = _g2++;
					_g12.push(k);
				}
				avl = _g12;
				i = 0;
				al = n;
				sign = false;
			} else {
				console.log("Warning: shape too complex to fill");
				return [];
			}
		}
	}
	tgs.push(avl[0]);
	tgs.push(avl[1]);
	tgs.push(avl[2]);
	return tgs;
};
openfl__$internal_renderer_PolyK._PointInTriangle = function(px,py,ax,ay,bx,by,cx,cy) {
	var v0x = cx - ax | 0;
	var v0y = cy - ay | 0;
	var v1x = bx - ax | 0;
	var v1y = by - ay | 0;
	var v2x = px - ax | 0;
	var v2y = py - ay | 0;
	var dot00 = v0x * v0x + v0y * v0y;
	var dot01 = v0x * v1x + v0y * v1y;
	var dot02 = v0x * v2x + v0y * v2y;
	var dot11 = v1x * v1x + v1y * v1y;
	var dot12 = v1x * v2x + v1y * v2y;
	var invDenom = 1 / (dot00 * dot11 - dot01 * dot01);
	var u = (dot11 * dot02 - dot01 * dot12) * invDenom;
	var v = (dot00 * dot12 - dot01 * dot02) * invDenom;
	return u >= 0 && v >= 0 && u + v < 1;
};
openfl__$internal_renderer_PolyK._convex = function(ax,ay,bx,by,cx,cy,sign) {
	return (ay - by) * (cx - bx) + (bx - ax) * (cy - by) >= 0 == sign;
};
var openfl__$internal_renderer_RenderSession = function() {
	this.activeTextures = 0;
};
$hxClasses["openfl._internal.renderer.RenderSession"] = openfl__$internal_renderer_RenderSession;
openfl__$internal_renderer_RenderSession.__name__ = true;
openfl__$internal_renderer_RenderSession.prototype = {
	__class__: openfl__$internal_renderer_RenderSession
};
var openfl__$internal_renderer_cairo_CairoBitmap = function() { };
$hxClasses["openfl._internal.renderer.cairo.CairoBitmap"] = openfl__$internal_renderer_cairo_CairoBitmap;
openfl__$internal_renderer_cairo_CairoBitmap.__name__ = true;
openfl__$internal_renderer_cairo_CairoBitmap.render = function(bitmap,renderSession) {
	if(!bitmap.__renderable || bitmap.__worldAlpha <= 0) return;
	var cairo = renderSession.cairo;
	if(bitmap.bitmapData != null && bitmap.bitmapData.__isValid) {
		if(bitmap.__mask != null) renderSession.maskManager.pushMask(bitmap.__mask);
		var transform = bitmap.__worldTransform;
		var scrollRect = bitmap.get_scrollRect();
		if(renderSession.roundPixels) {
			var matrix = transform.__toMatrix3();
			matrix.tx = Math.round(matrix.tx);
			matrix.ty = Math.round(matrix.ty);
			cairo.set_matrix(matrix);
		} else cairo.set_matrix(transform.__toMatrix3());
		var surface = bitmap.bitmapData.getSurface();
		if(surface != null) {
			var pattern = lime_graphics_cairo__$CairoPattern_CairoPattern_$Impl_$.createForSurface(surface);
			lime_graphics_cairo__$CairoPattern_CairoPattern_$Impl_$.set_filter(pattern,bitmap.smoothing?1:3);
			if(scrollRect != null) {
				cairo.pushGroup();
				cairo.set_source(pattern);
				cairo.newPath();
				cairo.rectangle(scrollRect.x,scrollRect.y,scrollRect.width,scrollRect.height);
				cairo.fill();
				cairo.popGroupToSource();
			} else cairo.set_source(pattern);
			if(bitmap.__worldAlpha == 1) cairo.paint(); else cairo.paintWithAlpha(bitmap.__worldAlpha);
		}
		if(bitmap.__mask != null) renderSession.maskManager.popMask();
	}
};
var openfl__$internal_renderer_cairo_CairoGraphics = function() { };
$hxClasses["openfl._internal.renderer.cairo.CairoGraphics"] = openfl__$internal_renderer_cairo_CairoGraphics;
openfl__$internal_renderer_cairo_CairoGraphics.__name__ = true;
openfl__$internal_renderer_cairo_CairoGraphics.cairo = null;
openfl__$internal_renderer_cairo_CairoGraphics.drawRoundRect = function(x,y,width,height,rx,ry) {
	if(ry == -1) ry = rx;
	rx *= 0.5;
	ry *= 0.5;
	if(rx > width / 2) rx = width / 2;
	if(ry > height / 2) ry = height / 2;
	var xe = x + width;
	var ye = y + height;
	var cx1 = -rx + rx * openfl__$internal_renderer_cairo_CairoGraphics.SIN45;
	var cx2 = -rx + rx * openfl__$internal_renderer_cairo_CairoGraphics.TAN22;
	var cy1 = -ry + ry * openfl__$internal_renderer_cairo_CairoGraphics.SIN45;
	var cy2 = -ry + ry * openfl__$internal_renderer_cairo_CairoGraphics.TAN22;
	openfl__$internal_renderer_cairo_CairoGraphics.cairo.moveTo(xe,ye - ry);
	openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo(xe,ye + cy2,xe + cx1,ye + cy1);
	openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo(xe + cx2,ye,xe - rx,ye);
	openfl__$internal_renderer_cairo_CairoGraphics.cairo.lineTo(x + rx,ye);
	openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo(x - cx2,ye,x - cx1,ye + cy1);
	openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo(x,ye + cy2,x,ye - ry);
	openfl__$internal_renderer_cairo_CairoGraphics.cairo.lineTo(x,y + ry);
	openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo(x,y - cy2,x - cx1,y - cy1);
	openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo(x - cx2,y,x + rx,y);
	openfl__$internal_renderer_cairo_CairoGraphics.cairo.lineTo(xe - rx,y);
	openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo(xe + cx2,y,xe + cx1,y - cy1);
	openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo(xe,y - cy2,xe,y + ry);
	openfl__$internal_renderer_cairo_CairoGraphics.cairo.lineTo(xe,ye - ry);
};
openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo = function(cx,cy,x,y) {
	var current = null;
	if(!openfl__$internal_renderer_cairo_CairoGraphics.cairo.get_hasCurrentPoint()) {
		openfl__$internal_renderer_cairo_CairoGraphics.cairo.moveTo(cx,cy);
		current = new lime_math_Vector2(cx,cy);
	} else current = openfl__$internal_renderer_cairo_CairoGraphics.cairo.get_currentPoint();
	var cx1 = current.x + 0.66666666666666663 * (cx - current.x);
	var cy1 = current.y + 0.66666666666666663 * (cy - current.y);
	var cx2 = x + 0.66666666666666663 * (cx - x);
	var cy2 = y + 0.66666666666666663 * (cy - y);
	openfl__$internal_renderer_cairo_CairoGraphics.cairo.curveTo(cx1,cy1,cx2,cy2,x,y);
};
openfl__$internal_renderer_cairo_CairoGraphics.renderMask = function(graphics,renderSession) {
	if(graphics.__commands.get_length() != 0) {
		var cairo = renderSession.cairo;
		var positionX = 0.0;
		var positionY = 0.0;
		var offsetX = 0;
		var offsetY = 0;
		var data = new openfl__$internal_renderer_DrawCommandReader(graphics.__commands);
		var _g = 0;
		var _g1 = graphics.__commands.types;
		while(_g < _g1.length) {
			var type = _g1[_g];
			++_g;
			switch(type[1]) {
			case 3:
				var c;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO;
				c = data;
				cairo.curveTo(c.buffer.f[c.fPos] - offsetX,c.buffer.f[c.fPos + 1] - offsetY,c.buffer.f[c.fPos + 3] - offsetX,c.buffer.f[c.fPos + 4] - offsetY,c.buffer.f[c.fPos + 5] - offsetX,c.buffer.f[c.fPos + 6] - offsetY);
				positionX = c.buffer.f[c.fPos + 5];
				positionY = c.buffer.f[c.fPos + 5];
				break;
			case 4:
				var c1;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CURVE_TO;
				c1 = data;
				openfl__$internal_renderer_cairo_CairoGraphics.quadraticCurveTo(c1.buffer.f[c1.fPos] - offsetX,c1.buffer.f[c1.fPos + 1] - offsetY,c1.buffer.f[c1.fPos + 2] - offsetX,c1.buffer.f[c1.fPos + 3] - offsetY);
				positionX = c1.buffer.f[c1.fPos + 2];
				positionY = c1.buffer.f[c1.fPos + 3];
				break;
			case 5:
				var c2;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE;
				c2 = data;
				cairo.arc(c2.buffer.f[c2.fPos] - offsetX,c2.buffer.f[c2.fPos + 1] - offsetY,c2.buffer.f[c2.fPos + 2],0,Math.PI * 2);
				break;
			case 6:
				var c3;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE;
				c3 = data;
				var x = c3.buffer.f[c3.fPos];
				var y = c3.buffer.f[c3.fPos + 1];
				var width = c3.buffer.f[c3.fPos + 2];
				var height = c3.buffer.f[c3.fPos + 3];
				x -= offsetX;
				y -= offsetY;
				var kappa = .5522848;
				var ox = width / 2 * kappa;
				var oy = height / 2 * kappa;
				var xe = x + width;
				var ye = y + height;
				var xm = x + width / 2;
				var ym = y + height / 2;
				cairo.moveTo(x,ym);
				cairo.curveTo(x,ym - oy,xm - ox,y,xm,y);
				cairo.curveTo(xm + ox,y,xe,ym - oy,xe,ym);
				cairo.curveTo(xe,ym + oy,xm + ox,ye,xm,ye);
				cairo.curveTo(xm - ox,ye,x,ym + oy,x,ym);
				break;
			case 8:
				var c4;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_RECT;
				c4 = data;
				cairo.rectangle(c4.buffer.f[c4.fPos] - offsetX,c4.buffer.f[c4.fPos + 1] - offsetY,c4.buffer.f[c4.fPos + 2],c4.buffer.f[c4.fPos + 3]);
				break;
			case 9:
				var c5;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT;
				c5 = data;
				openfl__$internal_renderer_cairo_CairoGraphics.drawRoundRect(c5.buffer.f[c5.fPos] - offsetX,c5.buffer.f[c5.fPos + 1] - offsetY,c5.buffer.f[c5.fPos + 2],c5.buffer.f[c5.fPos + 3],c5.buffer.f[c5.fPos + 4],c5.buffer.f[c5.fPos + 5]);
				break;
			case 16:
				var c6;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_TO;
				c6 = data;
				cairo.lineTo(c6.buffer.f[c6.fPos] - offsetX,c6.buffer.f[c6.fPos + 1] - offsetY);
				positionX = c6.buffer.f[c6.fPos];
				positionY = c6.buffer.f[c6.fPos + 1];
				break;
			case 17:
				var c7;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.MOVE_TO;
				c7 = data;
				cairo.moveTo(c7.buffer.f[c7.fPos] - offsetX,c7.buffer.f[c7.fPos + 1] - offsetY);
				positionX = c7.buffer.f[c7.fPos];
				positionY = c7.buffer.f[c7.fPos + 1];
				break;
			default:
				data.advance();
				data.prev = type;
			}
		}
		data.destroy();
	}
};
var openfl__$internal_renderer_cairo_CairoMaskManager = function(renderSession) {
	openfl__$internal_renderer_AbstractMaskManager.call(this,renderSession);
};
$hxClasses["openfl._internal.renderer.cairo.CairoMaskManager"] = openfl__$internal_renderer_cairo_CairoMaskManager;
openfl__$internal_renderer_cairo_CairoMaskManager.__name__ = true;
openfl__$internal_renderer_cairo_CairoMaskManager.__super__ = openfl__$internal_renderer_AbstractMaskManager;
openfl__$internal_renderer_cairo_CairoMaskManager.prototype = $extend(openfl__$internal_renderer_AbstractMaskManager.prototype,{
	pushMask: function(mask) {
		var cairo = this.renderSession.cairo;
		cairo.save();
		var transform = mask.__getWorldTransform();
		cairo.set_matrix(transform.__toMatrix3());
		cairo.newPath();
		mask.__renderCairoMask(this.renderSession);
		cairo.clip();
	}
	,pushRect: function(rect,transform) {
		var cairo = this.renderSession.cairo;
		cairo.save();
		cairo.set_matrix(new lime_math_Matrix3(transform.a,transform.c,transform.b,transform.d,transform.tx,transform.ty));
		cairo.newPath();
		cairo.rectangle(rect.x,rect.y,rect.width,rect.height);
		cairo.clip();
	}
	,popMask: function() {
		this.renderSession.cairo.restore();
	}
	,popRect: function() {
		this.renderSession.context.restore();
	}
	,__class__: openfl__$internal_renderer_cairo_CairoMaskManager
});
var openfl__$internal_renderer_cairo_CairoRenderer = function(width,height,cairo) {
	openfl__$internal_renderer_AbstractRenderer.call(this,width,height);
	this.cairo = cairo;
	this.renderSession = new openfl__$internal_renderer_RenderSession();
	this.renderSession.cairo = cairo;
	this.renderSession.roundPixels = true;
	this.renderSession.renderer = this;
	this.renderSession.maskManager = new openfl__$internal_renderer_cairo_CairoMaskManager(this.renderSession);
};
$hxClasses["openfl._internal.renderer.cairo.CairoRenderer"] = openfl__$internal_renderer_cairo_CairoRenderer;
openfl__$internal_renderer_cairo_CairoRenderer.__name__ = true;
openfl__$internal_renderer_cairo_CairoRenderer.__super__ = openfl__$internal_renderer_AbstractRenderer;
openfl__$internal_renderer_cairo_CairoRenderer.prototype = $extend(openfl__$internal_renderer_AbstractRenderer.prototype,{
	render: function(stage) {
		this.cairo.identityMatrix();
		if(stage.__clearBeforeRender) {
			this.cairo.setSourceRGB(stage.__colorSplit[0],stage.__colorSplit[1],stage.__colorSplit[2]);
			this.cairo.paint();
		}
		stage.__renderCairo(this.renderSession);
	}
	,__class__: openfl__$internal_renderer_cairo_CairoRenderer
});
var openfl__$internal_renderer_cairo_CairoShape = function() { };
$hxClasses["openfl._internal.renderer.cairo.CairoShape"] = openfl__$internal_renderer_cairo_CairoShape;
openfl__$internal_renderer_cairo_CairoShape.__name__ = true;
openfl__$internal_renderer_cairo_CairoShape.render = function(shape,renderSession) {
};
var openfl__$internal_renderer_cairo_CairoTextField = function() { };
$hxClasses["openfl._internal.renderer.cairo.CairoTextField"] = openfl__$internal_renderer_cairo_CairoTextField;
openfl__$internal_renderer_cairo_CairoTextField.__name__ = true;
openfl__$internal_renderer_cairo_CairoTextField.render = function(textField,renderSession) {
};
var openfl__$internal_renderer_canvas_CanvasBitmap = function() { };
$hxClasses["openfl._internal.renderer.canvas.CanvasBitmap"] = openfl__$internal_renderer_canvas_CanvasBitmap;
openfl__$internal_renderer_canvas_CanvasBitmap.__name__ = true;
openfl__$internal_renderer_canvas_CanvasBitmap.render = function(bitmap,renderSession) {
	if(!bitmap.__renderable || bitmap.__worldAlpha <= 0) return;
	var context = renderSession.context;
	if(bitmap.bitmapData != null && bitmap.bitmapData.__isValid) {
		if(bitmap.__mask != null) renderSession.maskManager.pushMask(bitmap.__mask);
		bitmap.bitmapData.__sync();
		context.globalAlpha = bitmap.__worldAlpha;
		var transform = bitmap.__worldTransform;
		var scrollRect = bitmap.get_scrollRect();
		if(renderSession.roundPixels) context.setTransform(transform.a,transform.b,transform.c,transform.d,transform.tx | 0,transform.ty | 0); else context.setTransform(transform.a,transform.b,transform.c,transform.d,transform.tx,transform.ty);
		if(!bitmap.smoothing) {
			context.mozImageSmoothingEnabled = false;
			context.msImageSmoothingEnabled = false;
			context.imageSmoothingEnabled = false;
		}
		if(scrollRect == null) context.drawImage(bitmap.bitmapData.image.get_src(),0,0); else context.drawImage(bitmap.bitmapData.image.get_src(),scrollRect.x,scrollRect.y,scrollRect.width,scrollRect.height,scrollRect.x,scrollRect.y,scrollRect.width,scrollRect.height);
		if(!bitmap.smoothing) {
			context.mozImageSmoothingEnabled = true;
			context.msImageSmoothingEnabled = true;
			context.imageSmoothingEnabled = true;
		}
		if(bitmap.__mask != null) renderSession.maskManager.popMask();
	}
};
var openfl__$internal_renderer_canvas_CanvasGraphics = function() { };
$hxClasses["openfl._internal.renderer.canvas.CanvasGraphics"] = openfl__$internal_renderer_canvas_CanvasGraphics;
openfl__$internal_renderer_canvas_CanvasGraphics.__name__ = true;
openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill = null;
openfl__$internal_renderer_canvas_CanvasGraphics.bitmapRepeat = null;
openfl__$internal_renderer_canvas_CanvasGraphics.bounds = null;
openfl__$internal_renderer_canvas_CanvasGraphics.graphics = null;
openfl__$internal_renderer_canvas_CanvasGraphics.hasFill = null;
openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke = null;
openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting = null;
openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix = null;
openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix = null;
openfl__$internal_renderer_canvas_CanvasGraphics.context = null;
openfl__$internal_renderer_canvas_CanvasGraphics.closePath = function() {
	if(openfl__$internal_renderer_canvas_CanvasGraphics.context.strokeStyle == null) return;
	openfl__$internal_renderer_canvas_CanvasGraphics.context.closePath();
	openfl__$internal_renderer_canvas_CanvasGraphics.context.stroke();
	openfl__$internal_renderer_canvas_CanvasGraphics.context.beginPath();
};
openfl__$internal_renderer_canvas_CanvasGraphics.createBitmapFill = function(bitmap,bitmapRepeat) {
	bitmap.__sync();
	return openfl__$internal_renderer_canvas_CanvasGraphics.context.createPattern(bitmap.image.get_src(),bitmapRepeat?"repeat":"no-repeat");
};
openfl__$internal_renderer_canvas_CanvasGraphics.createGradientPattern = function(type,colors,alphas,ratios,matrix,spreadMethod,interpolationMethod,focalPointRatio) {
	var gradientFill = null;
	switch(type[1]) {
	case 0:
		if(matrix == null) matrix = new openfl_geom_Matrix();
		var point = matrix.transformPoint(new openfl_geom_Point(1638.4,0));
		gradientFill = openfl__$internal_renderer_canvas_CanvasGraphics.context.createRadialGradient(matrix.tx,matrix.ty,0,matrix.tx,matrix.ty,(point.x - matrix.tx) / 2);
		break;
	case 1:
		var matrix1;
		if(matrix != null) matrix1 = matrix; else matrix1 = new openfl_geom_Matrix();
		var point1 = matrix1.transformPoint(new openfl_geom_Point(-819.2,0));
		var point2 = matrix1.transformPoint(new openfl_geom_Point(819.2,0));
		gradientFill = openfl__$internal_renderer_canvas_CanvasGraphics.context.createLinearGradient(point1.x,point1.y,point2.x,point2.y);
		break;
	}
	var _g1 = 0;
	var _g = colors.length;
	while(_g1 < _g) {
		var i = _g1++;
		var rgb = colors[i];
		var alpha = alphas[i];
		var r = (rgb & 16711680) >>> 16;
		var g = (rgb & 65280) >>> 8;
		var b = rgb & 255;
		var ratio = ratios[i] / 255;
		if(ratio < 0) ratio = 0;
		if(ratio > 1) ratio = 1;
		gradientFill.addColorStop(ratio,"rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")");
	}
	return gradientFill;
};
openfl__$internal_renderer_canvas_CanvasGraphics.createTempPatternCanvas = function(bitmap,repeat,width,height) {
	var canvas = window.document.createElement("canvas");
	var context = canvas.getContext("2d");
	canvas.width = width;
	canvas.height = height;
	context.fillStyle = context.createPattern(bitmap.image.get_src(),repeat?"repeat":"no-repeat");
	context.beginPath();
	context.moveTo(0,0);
	context.lineTo(0,height);
	context.lineTo(width,height);
	context.lineTo(width,0);
	context.lineTo(0,0);
	context.closePath();
	if(!openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting) context.fill();
	return canvas;
};
openfl__$internal_renderer_canvas_CanvasGraphics.drawRoundRect = function(x,y,width,height,rx,ry) {
	if(ry == -1) ry = rx;
	rx *= 0.5;
	ry *= 0.5;
	if(rx > width / 2) rx = width / 2;
	if(ry > height / 2) ry = height / 2;
	var xe = x + width;
	var ye = y + height;
	var cx1 = -rx + rx * openfl__$internal_renderer_canvas_CanvasGraphics.SIN45;
	var cx2 = -rx + rx * openfl__$internal_renderer_canvas_CanvasGraphics.TAN22;
	var cy1 = -ry + ry * openfl__$internal_renderer_canvas_CanvasGraphics.SIN45;
	var cy2 = -ry + ry * openfl__$internal_renderer_canvas_CanvasGraphics.TAN22;
	openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(xe,ye - ry);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(xe,ye + cy2,xe + cx1,ye + cy1);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(xe + cx2,ye,xe - rx,ye);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(x + rx,ye);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(x - cx2,ye,x - cx1,ye + cy1);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(x,ye + cy2,x,ye - ry);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(x,y + ry);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(x,y - cy2,x - cx1,y - cy1);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(x - cx2,y,x + rx,y);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(xe - rx,y);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(xe + cx2,y,xe + cx1,y - cy1);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(xe,y - cy2,xe,y + ry);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(xe,ye - ry);
};
openfl__$internal_renderer_canvas_CanvasGraphics.endFill = function() {
	openfl__$internal_renderer_canvas_CanvasGraphics.context.beginPath();
	openfl__$internal_renderer_canvas_CanvasGraphics.playCommands(openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands,false);
	openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.clear();
};
openfl__$internal_renderer_canvas_CanvasGraphics.endStroke = function() {
	openfl__$internal_renderer_canvas_CanvasGraphics.context.beginPath();
	openfl__$internal_renderer_canvas_CanvasGraphics.playCommands(openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands,true);
	openfl__$internal_renderer_canvas_CanvasGraphics.context.closePath();
	openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.clear();
};
openfl__$internal_renderer_canvas_CanvasGraphics.hitTest = function(graphics,x,y) {
	if(graphics.__commands.get_length() == 0 || openfl__$internal_renderer_canvas_CanvasGraphics.bounds == null || openfl__$internal_renderer_canvas_CanvasGraphics.bounds.width <= 0 || openfl__$internal_renderer_canvas_CanvasGraphics.bounds.height <= 0) return false; else {
		openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting = true;
		x -= openfl__$internal_renderer_canvas_CanvasGraphics.bounds.x;
		y -= openfl__$internal_renderer_canvas_CanvasGraphics.bounds.y;
		if(graphics.__canvas == null) {
			graphics.__canvas = window.document.createElement("canvas");
			graphics.__context = graphics.__canvas.getContext("2d");
		}
		openfl__$internal_renderer_canvas_CanvasGraphics.context = graphics.__context;
		openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.clear();
		openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.clear();
		openfl__$internal_renderer_canvas_CanvasGraphics.hasFill = false;
		openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke = false;
		openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill = null;
		openfl__$internal_renderer_canvas_CanvasGraphics.bitmapRepeat = false;
		openfl__$internal_renderer_canvas_CanvasGraphics.context.beginPath();
		var data = new openfl__$internal_renderer_DrawCommandReader(graphics.__commands);
		var _g = 0;
		var _g1 = graphics.__commands.types;
		while(_g < _g1.length) {
			var type = _g1[_g];
			++_g;
			switch(type[1]) {
			case 3:
				var c;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO;
				c = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.cubicCurveTo(c.buffer.f[c.fPos],c.buffer.f[c.fPos + 1],c.buffer.f[c.fPos + 3],c.buffer.f[c.fPos + 4],c.buffer.f[c.fPos + 5],c.buffer.f[c.fPos + 6]);
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.cubicCurveTo(c.buffer.f[c.fPos],c.buffer.f[c.fPos + 1],c.buffer.f[c.fPos + 3],c.buffer.f[c.fPos + 4],c.buffer.f[c.fPos + 5],c.buffer.f[c.fPos + 6]);
				break;
			case 4:
				var c1;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CURVE_TO;
				c1 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.curveTo(c1.buffer.f[c1.fPos],c1.buffer.f[c1.fPos + 1],c1.buffer.f[c1.fPos + 2],c1.buffer.f[c1.fPos + 3]);
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.curveTo(c1.buffer.f[c1.fPos],c1.buffer.f[c1.fPos + 1],c1.buffer.f[c1.fPos + 2],c1.buffer.f[c1.fPos + 3]);
				break;
			case 16:
				var c2;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_TO;
				c2 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.lineTo(c2.buffer.f[c2.fPos],c2.buffer.f[c2.fPos + 1]);
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.lineTo(c2.buffer.f[c2.fPos],c2.buffer.f[c2.fPos + 1]);
				break;
			case 17:
				var c3;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.MOVE_TO;
				c3 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.moveTo(c3.buffer.f[c3.fPos],c3.buffer.f[c3.fPos + 1]);
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.moveTo(c3.buffer.f[c3.fPos],c3.buffer.f[c3.fPos + 1]);
				break;
			case 14:
				var c4;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_GRADIENT_STYLE;
				c4 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.lineGradientStyle(c4.buffer.o[c4.oPos],c4.buffer.ii[c4.iiPos],c4.buffer.ff[c4.ffPos],c4.buffer.ii[c4.iiPos + 1],c4.buffer.o[c4.oPos + 1],c4.buffer.o[c4.oPos + 2],c4.buffer.o[c4.oPos + 3],c4.buffer.o[c4.oPos + 4]);
				break;
			case 13:
				var c5;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_BITMAP_STYLE;
				c5 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.lineBitmapStyle(c5.buffer.o[c5.oPos],c5.buffer.o[c5.oPos + 1],c5.buffer.b[c5.bPos],c5.buffer.b[c5.bPos + 1]);
				break;
			case 15:
				var c6;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_STYLE;
				c6 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.lineStyle(c6.buffer.o[c6.oPos],c6.buffer.o[c6.oPos + 1],1,c6.buffer.o[c6.oPos + 3],c6.buffer.o[c6.oPos + 4],c6.buffer.o[c6.oPos + 5],c6.buffer.o[c6.oPos + 6],c6.buffer.o[c6.oPos + 7]);
				break;
			case 12:
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.END_FILL;
				data;
				openfl__$internal_renderer_canvas_CanvasGraphics.endFill();
				openfl__$internal_renderer_canvas_CanvasGraphics.endStroke();
				if(openfl__$internal_renderer_canvas_CanvasGraphics.hasFill && openfl__$internal_renderer_canvas_CanvasGraphics.context.isPointInPath(x,y)) {
					data.destroy();
					return true;
				}
				if(openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke && openfl__$internal_renderer_canvas_CanvasGraphics.context.isPointInStroke(x,y)) {
					data.destroy();
					return true;
				}
				openfl__$internal_renderer_canvas_CanvasGraphics.hasFill = false;
				openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill = null;
				break;
			case 0:case 1:case 2:
				openfl__$internal_renderer_canvas_CanvasGraphics.endFill();
				openfl__$internal_renderer_canvas_CanvasGraphics.endStroke();
				if(openfl__$internal_renderer_canvas_CanvasGraphics.hasFill && openfl__$internal_renderer_canvas_CanvasGraphics.context.isPointInPath(x,y)) {
					data.destroy();
					return true;
				}
				if(openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke && openfl__$internal_renderer_canvas_CanvasGraphics.context.isPointInStroke(x,y)) {
					data.destroy();
					return true;
				}
				if(type == openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL) {
					var c7;
					data.advance();
					data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL;
					c7 = data;
					openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.beginBitmapFill(c7.buffer.o[c7.oPos],c7.buffer.o[c7.oPos + 1],c7.buffer.b[c7.bPos],c7.buffer.b[c7.bPos + 1]);
					openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.beginBitmapFill(c7.buffer.o[c7.oPos],c7.buffer.o[c7.oPos + 1],c7.buffer.b[c7.bPos],c7.buffer.b[c7.bPos + 1]);
				} else if(type == openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL) {
					var c8;
					data.advance();
					data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL;
					c8 = data;
					openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.beginGradientFill(c8.buffer.o[c8.oPos],c8.buffer.ii[c8.iiPos],c8.buffer.ff[c8.ffPos],c8.buffer.ii[c8.iiPos + 1],c8.buffer.o[c8.oPos + 1],c8.buffer.o[c8.oPos + 2],c8.buffer.o[c8.oPos + 3],c8.buffer.o[c8.oPos + 4]);
					openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.beginGradientFill(c8.buffer.o[c8.oPos],c8.buffer.ii[c8.iiPos],c8.buffer.ff[c8.ffPos],c8.buffer.ii[c8.iiPos + 1],c8.buffer.o[c8.oPos + 1],c8.buffer.o[c8.oPos + 2],c8.buffer.o[c8.oPos + 3],c8.buffer.o[c8.oPos + 4]);
				} else {
					var c9;
					data.advance();
					data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_FILL;
					c9 = data;
					openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.beginFill(c9.buffer.i[c9.iPos],1);
					openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.beginFill(c9.buffer.i[c9.iPos],1);
				}
				break;
			case 5:
				var c10;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE;
				c10 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.drawCircle(c10.buffer.f[c10.fPos],c10.buffer.f[c10.fPos + 1],c10.buffer.f[c10.fPos + 2]);
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.drawCircle(c10.buffer.f[c10.fPos],c10.buffer.f[c10.fPos + 1],c10.buffer.f[c10.fPos + 2]);
				break;
			case 6:
				var c11;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE;
				c11 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.drawEllipse(c11.buffer.f[c11.fPos],c11.buffer.f[c11.fPos + 1],c11.buffer.f[c11.fPos + 2],c11.buffer.f[c11.fPos + 3]);
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.drawEllipse(c11.buffer.f[c11.fPos],c11.buffer.f[c11.fPos + 1],c11.buffer.f[c11.fPos + 2],c11.buffer.f[c11.fPos + 3]);
				break;
			case 8:
				var c12;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_RECT;
				c12 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.drawRect(c12.buffer.f[c12.fPos],c12.buffer.f[c12.fPos + 1],c12.buffer.f[c12.fPos + 2],c12.buffer.f[c12.fPos + 3]);
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.drawRect(c12.buffer.f[c12.fPos],c12.buffer.f[c12.fPos + 1],c12.buffer.f[c12.fPos + 2],c12.buffer.f[c12.fPos + 3]);
				break;
			case 9:
				var c13;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT;
				c13 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.drawRoundRect(c13.buffer.f[c13.fPos],c13.buffer.f[c13.fPos + 1],c13.buffer.f[c13.fPos + 2],c13.buffer.f[c13.fPos + 3],c13.buffer.f[c13.fPos + 4],c13.buffer.f[c13.fPos + 5]);
				openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.drawRoundRect(c13.buffer.f[c13.fPos],c13.buffer.f[c13.fPos + 1],c13.buffer.f[c13.fPos + 2],c13.buffer.f[c13.fPos + 3],c13.buffer.f[c13.fPos + 4],c13.buffer.f[c13.fPos + 5]);
				break;
			default:
				data.advance();
				data.prev = type;
			}
		}
		if(openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.get_length() > 0) openfl__$internal_renderer_canvas_CanvasGraphics.endFill();
		if(openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.get_length() > 0) openfl__$internal_renderer_canvas_CanvasGraphics.endStroke();
		data.destroy();
		if(openfl__$internal_renderer_canvas_CanvasGraphics.hasFill && openfl__$internal_renderer_canvas_CanvasGraphics.context.isPointInPath(x,y)) return true;
		if(openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke && openfl__$internal_renderer_canvas_CanvasGraphics.context.isPointInStroke(x,y)) return true;
	}
	return false;
};
openfl__$internal_renderer_canvas_CanvasGraphics.normalizeUVT = function(uvt,skipT) {
	if(skipT == null) skipT = false;
	var max = -Infinity;
	var tmp = -Infinity;
	var len = uvt.length;
	var _g1 = 1;
	var _g = len + 1;
	while(_g1 < _g) {
		var t = _g1++;
		if(skipT && t % 3 == 0) continue;
		tmp = uvt.data[t - 1];
		if(max < tmp) max = tmp;
	}
	var result;
	var this1;
	this1 = new openfl_VectorData();
	var this2;
	this2 = new Array(0);
	this1.data = this2;
	this1.length = 0;
	this1.fixed = false;
	result = this1;
	var _g11 = 1;
	var _g2 = len + 1;
	while(_g11 < _g2) {
		var t1 = _g11++;
		if(skipT && t1 % 3 == 0) continue;
		if(!result.fixed) {
			result.length++;
			if(result.data.length < result.length) {
				var data;
				var this3;
				this3 = new Array(result.data.length + 10);
				data = this3;
				haxe_ds__$Vector_Vector_$Impl_$.blit(result.data,0,data,0,result.data.length);
				result.data = data;
			}
			result.data[result.length - 1] = uvt.data[t1 - 1] / max;
		}
		result.length;
	}
	return { max : max, uvt : result};
};
openfl__$internal_renderer_canvas_CanvasGraphics.playCommands = function(commands,stroke) {
	if(stroke == null) stroke = false;
	openfl__$internal_renderer_canvas_CanvasGraphics.bounds = openfl__$internal_renderer_canvas_CanvasGraphics.graphics.__bounds;
	var offsetX = openfl__$internal_renderer_canvas_CanvasGraphics.bounds.x;
	var offsetY = openfl__$internal_renderer_canvas_CanvasGraphics.bounds.y;
	var positionX = 0.0;
	var positionY = 0.0;
	var closeGap = false;
	var startX = 0.0;
	var startY = 0.0;
	var data = new openfl__$internal_renderer_DrawCommandReader(commands);
	var _g = 0;
	var _g1 = commands.types;
	while(_g < _g1.length) {
		var type = _g1[_g];
		++_g;
		switch(type[1]) {
		case 3:
			var c;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO;
			c = data;
			openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(c.buffer.f[c.fPos] - offsetX,c.buffer.f[c.fPos + 1] - offsetY,c.buffer.f[c.fPos + 3] - offsetX,c.buffer.f[c.fPos + 4] - offsetY,c.buffer.f[c.fPos + 5] - offsetX,c.buffer.f[c.fPos + 6] - offsetY);
			break;
		case 4:
			var c1;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.CURVE_TO;
			c1 = data;
			openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(c1.buffer.f[c1.fPos] - offsetX,c1.buffer.f[c1.fPos + 1] - offsetY,c1.buffer.f[c1.fPos + 2] - offsetX,c1.buffer.f[c1.fPos + 3] - offsetY);
			break;
		case 5:
			var c2;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE;
			c2 = data;
			openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(c2.buffer.f[c2.fPos] - offsetX + c2.buffer.f[c2.fPos + 2],c2.buffer.f[c2.fPos + 1] - offsetY);
			openfl__$internal_renderer_canvas_CanvasGraphics.context.arc(c2.buffer.f[c2.fPos] - offsetX,c2.buffer.f[c2.fPos + 1] - offsetY,c2.buffer.f[c2.fPos + 2],0,Math.PI * 2,true);
			break;
		case 6:
			var c3;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE;
			c3 = data;
			var x = c3.buffer.f[c3.fPos];
			var y = c3.buffer.f[c3.fPos + 1];
			var width = c3.buffer.f[c3.fPos + 2];
			var height = c3.buffer.f[c3.fPos + 3];
			x -= offsetX;
			y -= offsetY;
			var kappa = .5522848;
			var ox = width / 2 * kappa;
			var oy = height / 2 * kappa;
			var xe = x + width;
			var ye = y + height;
			var xm = x + width / 2;
			var ym = y + height / 2;
			openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(x,ym);
			openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(x,ym - oy,xm - ox,y,xm,y);
			openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(xm + ox,y,xe,ym - oy,xe,ym);
			openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(xe,ym + oy,xm + ox,ye,xm,ye);
			openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(xm - ox,ye,x,ym + oy,x,ym);
			break;
		case 9:
			var c4;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT;
			c4 = data;
			openfl__$internal_renderer_canvas_CanvasGraphics.drawRoundRect(c4.buffer.f[c4.fPos] - offsetX,c4.buffer.f[c4.fPos + 1] - offsetY,c4.buffer.f[c4.fPos + 2],c4.buffer.f[c4.fPos + 3],c4.buffer.f[c4.fPos + 4],c4.buffer.f[c4.fPos + 5]);
			break;
		case 16:
			var c5;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.LINE_TO;
			c5 = data;
			openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(c5.buffer.f[c5.fPos] - offsetX,c5.buffer.f[c5.fPos + 1] - offsetY);
			positionX = c5.buffer.f[c5.fPos];
			positionY = c5.buffer.f[c5.fPos + 1];
			break;
		case 17:
			var c6;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.MOVE_TO;
			c6 = data;
			openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(c6.buffer.f[c6.fPos] - offsetX,c6.buffer.f[c6.fPos + 1] - offsetY);
			positionX = c6.buffer.f[c6.fPos];
			positionY = c6.buffer.f[c6.fPos + 1];
			closeGap = true;
			startX = c6.buffer.f[c6.fPos];
			startY = c6.buffer.f[c6.fPos + 1];
			break;
		case 15:
			var c7;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.LINE_STYLE;
			c7 = data;
			if(stroke && openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke) {
				openfl__$internal_renderer_canvas_CanvasGraphics.context.closePath();
				if(!openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting) openfl__$internal_renderer_canvas_CanvasGraphics.context.stroke();
				openfl__$internal_renderer_canvas_CanvasGraphics.context.beginPath();
			}
			openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(positionX - offsetX,positionY - offsetY);
			if(c7.buffer.o[c7.oPos] == null) openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke = false; else {
				if(c7.buffer.o[c7.oPos] > 0) openfl__$internal_renderer_canvas_CanvasGraphics.context.lineWidth = c7.buffer.o[c7.oPos]; else openfl__$internal_renderer_canvas_CanvasGraphics.context.lineWidth = 1;
				if(c7.buffer.o[c7.oPos + 6] == null) openfl__$internal_renderer_canvas_CanvasGraphics.context.lineJoin = "round"; else openfl__$internal_renderer_canvas_CanvasGraphics.context.lineJoin = Std.string(c7.buffer.o[c7.oPos + 6]).toLowerCase();
				if(c7.buffer.o[c7.oPos + 5] == null) openfl__$internal_renderer_canvas_CanvasGraphics.context.lineCap = "round"; else {
					var _g2 = c7.buffer.o[c7.oPos + 5];
					switch(_g2[1]) {
					case 0:
						openfl__$internal_renderer_canvas_CanvasGraphics.context.lineCap = "butt";
						break;
					default:
						openfl__$internal_renderer_canvas_CanvasGraphics.context.lineCap = Std.string(c7.buffer.o[c7.oPos + 5]).toLowerCase();
					}
				}
				if(c7.buffer.o[c7.oPos + 7] == null) openfl__$internal_renderer_canvas_CanvasGraphics.context.miterLimit = 3; else openfl__$internal_renderer_canvas_CanvasGraphics.context.miterLimit = c7.buffer.o[c7.oPos + 7];
				if(c7.buffer.o[c7.oPos + 2] == 1 || c7.buffer.o[c7.oPos + 2] == null) if(c7.buffer.o[c7.oPos + 1] == null) openfl__$internal_renderer_canvas_CanvasGraphics.context.strokeStyle = "#000000"; else openfl__$internal_renderer_canvas_CanvasGraphics.context.strokeStyle = "#" + StringTools.hex(c7.buffer.o[c7.oPos + 1] & 16777215,6); else {
					var r = (c7.buffer.o[c7.oPos + 1] & 16711680) >>> 16;
					var g = (c7.buffer.o[c7.oPos + 1] & 65280) >>> 8;
					var b = c7.buffer.o[c7.oPos + 1] & 255;
					if(c7.buffer.o[c7.oPos + 1] == null) openfl__$internal_renderer_canvas_CanvasGraphics.context.strokeStyle = "#000000"; else openfl__$internal_renderer_canvas_CanvasGraphics.context.strokeStyle = "rgba(" + r + ", " + g + ", " + b + ", " + c7.buffer.o[c7.oPos + 2] + ")";
				}
				openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke = true;
			}
			break;
		case 14:
			var c8;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.LINE_GRADIENT_STYLE;
			c8 = data;
			if(stroke && openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke) openfl__$internal_renderer_canvas_CanvasGraphics.closePath();
			openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(positionX - offsetX,positionY - offsetY);
			openfl__$internal_renderer_canvas_CanvasGraphics.context.strokeStyle = openfl__$internal_renderer_canvas_CanvasGraphics.createGradientPattern(c8.buffer.o[c8.oPos],c8.buffer.ii[c8.iiPos],c8.buffer.ff[c8.ffPos],c8.buffer.ii[c8.iiPos + 1],c8.buffer.o[c8.oPos + 1],c8.buffer.o[c8.oPos + 2],c8.buffer.o[c8.oPos + 3],c8.buffer.o[c8.oPos + 4]);
			openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke = true;
			break;
		case 13:
			var c9;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.LINE_BITMAP_STYLE;
			c9 = data;
			if(stroke && openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke) openfl__$internal_renderer_canvas_CanvasGraphics.closePath();
			openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(positionX - offsetX,positionY - offsetY);
			openfl__$internal_renderer_canvas_CanvasGraphics.context.strokeStyle = openfl__$internal_renderer_canvas_CanvasGraphics.createBitmapFill(c9.buffer.o[c9.oPos],c9.buffer.b[c9.bPos]);
			openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke = true;
			break;
		case 0:
			var c10;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL;
			c10 = data;
			openfl__$internal_renderer_canvas_CanvasGraphics.context.fillStyle = openfl__$internal_renderer_canvas_CanvasGraphics.createBitmapFill(c10.buffer.o[c10.oPos],true);
			openfl__$internal_renderer_canvas_CanvasGraphics.hasFill = true;
			if(c10.buffer.o[c10.oPos + 1] != null) {
				openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix = c10.buffer.o[c10.oPos + 1];
				openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix = c10.buffer.o[c10.oPos + 1].clone();
				openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix.invert();
			} else {
				openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix = null;
				openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix = null;
			}
			break;
		case 1:
			var c11;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_FILL;
			c11 = data;
			if(c11.buffer.f[c11.fPos] < 0.005) openfl__$internal_renderer_canvas_CanvasGraphics.hasFill = false; else {
				if(c11.buffer.f[c11.fPos] == 1) openfl__$internal_renderer_canvas_CanvasGraphics.context.fillStyle = "#" + StringTools.hex(c11.buffer.i[c11.iPos],6); else {
					var r1 = (c11.buffer.i[c11.iPos] & 16711680) >>> 16;
					var g1 = (c11.buffer.i[c11.iPos] & 65280) >>> 8;
					var b1 = c11.buffer.i[c11.iPos] & 255;
					openfl__$internal_renderer_canvas_CanvasGraphics.context.fillStyle = "rgba(" + r1 + ", " + g1 + ", " + b1 + ", " + c11.buffer.f[c11.fPos] + ")";
				}
				openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill = null;
				openfl__$internal_renderer_canvas_CanvasGraphics.hasFill = true;
			}
			break;
		case 2:
			var c12;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL;
			c12 = data;
			openfl__$internal_renderer_canvas_CanvasGraphics.context.fillStyle = openfl__$internal_renderer_canvas_CanvasGraphics.createGradientPattern(c12.buffer.o[c12.oPos],c12.buffer.ii[c12.iiPos],c12.buffer.ff[c12.ffPos],c12.buffer.ii[c12.iiPos + 1],c12.buffer.o[c12.oPos + 1],c12.buffer.o[c12.oPos + 2],c12.buffer.o[c12.oPos + 3],c12.buffer.o[c12.oPos + 4]);
			openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill = null;
			openfl__$internal_renderer_canvas_CanvasGraphics.hasFill = true;
			break;
		case 8:
			var c13;
			data.advance();
			data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_RECT;
			c13 = data;
			var optimizationUsed = false;
			if(openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill != null) {
				var st = 0;
				var sr = 0;
				var sb = 0;
				var sl = 0;
				var canOptimizeMatrix = true;
				if(openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix != null) {
					if(openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix.b != 0 || openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix.c != 0) canOptimizeMatrix = false; else {
						var stl = openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix.transformPoint(new openfl_geom_Point(c13.buffer.f[c13.fPos],c13.buffer.f[c13.fPos + 1]));
						var sbr = openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix.transformPoint(new openfl_geom_Point(c13.buffer.f[c13.fPos] + c13.buffer.f[c13.fPos + 2],c13.buffer.f[c13.fPos + 1] + c13.buffer.f[c13.fPos + 3]));
						st = stl.y;
						sl = stl.x;
						sb = sbr.y;
						sr = sbr.x;
					}
				} else {
					st = c13.buffer.f[c13.fPos + 1];
					sl = c13.buffer.f[c13.fPos];
					sb = c13.buffer.f[c13.fPos + 1] + c13.buffer.f[c13.fPos + 3];
					sr = c13.buffer.f[c13.fPos] + c13.buffer.f[c13.fPos + 2];
				}
				if(canOptimizeMatrix && st >= 0 && sl >= 0 && sr <= openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill.width && sb <= openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill.height) {
					optimizationUsed = true;
					if(!openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting) openfl__$internal_renderer_canvas_CanvasGraphics.context.drawImage(openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill.image.get_src(),sl,st,sr - sl,sb - st,c13.buffer.f[c13.fPos] - offsetX,c13.buffer.f[c13.fPos + 1] - offsetY,c13.buffer.f[c13.fPos + 2],c13.buffer.f[c13.fPos + 3]);
				}
			}
			if(!optimizationUsed) openfl__$internal_renderer_canvas_CanvasGraphics.context.rect(c13.buffer.f[c13.fPos] - offsetX,c13.buffer.f[c13.fPos + 1] - offsetY,c13.buffer.f[c13.fPos + 2],c13.buffer.f[c13.fPos + 3]);
			break;
		default:
			data.advance();
			data.prev = type;
		}
	}
	data.destroy();
	if(stroke && openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke) {
		if(openfl__$internal_renderer_canvas_CanvasGraphics.hasFill && closeGap) openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(startX - offsetX,startY - offsetY); else if(closeGap && positionX == startX && positionY == startY) openfl__$internal_renderer_canvas_CanvasGraphics.context.closePath();
		if(!openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting) openfl__$internal_renderer_canvas_CanvasGraphics.context.stroke();
	}
	if(!stroke) {
		if(openfl__$internal_renderer_canvas_CanvasGraphics.hasFill || openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill != null) {
			openfl__$internal_renderer_canvas_CanvasGraphics.context.translate(-openfl__$internal_renderer_canvas_CanvasGraphics.bounds.x,-openfl__$internal_renderer_canvas_CanvasGraphics.bounds.y);
			if(openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix != null) {
				openfl__$internal_renderer_canvas_CanvasGraphics.context.transform(openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix.a,openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix.b,openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix.c,openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix.d,openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix.tx,openfl__$internal_renderer_canvas_CanvasGraphics.pendingMatrix.ty);
				if(!openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting) openfl__$internal_renderer_canvas_CanvasGraphics.context.fill();
				openfl__$internal_renderer_canvas_CanvasGraphics.context.transform(openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix.a,openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix.b,openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix.c,openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix.d,openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix.tx,openfl__$internal_renderer_canvas_CanvasGraphics.inversePendingMatrix.ty);
			} else if(!openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting) openfl__$internal_renderer_canvas_CanvasGraphics.context.fill();
			openfl__$internal_renderer_canvas_CanvasGraphics.context.translate(openfl__$internal_renderer_canvas_CanvasGraphics.bounds.x,openfl__$internal_renderer_canvas_CanvasGraphics.bounds.y);
			openfl__$internal_renderer_canvas_CanvasGraphics.context.closePath();
		}
	}
};
openfl__$internal_renderer_canvas_CanvasGraphics.render = function(graphics,renderSession) {
	var directRender = false;
	if(graphics.__dirty || directRender) {
		openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting = false;
		openfl__$internal_renderer_canvas_CanvasGraphics.graphics = graphics;
		openfl__$internal_renderer_canvas_CanvasGraphics.bounds = graphics.__bounds;
		if(!graphics.__visible || graphics.__commands.get_length() == 0 || openfl__$internal_renderer_canvas_CanvasGraphics.bounds == null || openfl__$internal_renderer_canvas_CanvasGraphics.bounds.width <= 0 || openfl__$internal_renderer_canvas_CanvasGraphics.bounds.height <= 0) {
			graphics.__canvas = null;
			graphics.__context = null;
			graphics.__bitmap = null;
		} else {
			if(directRender) {
				openfl__$internal_renderer_canvas_CanvasGraphics.context = renderSession.context;
				openfl__$internal_renderer_canvas_CanvasGraphics.bounds.setTo(0,0,openfl__$internal_renderer_canvas_CanvasGraphics.context.canvas.width,openfl__$internal_renderer_canvas_CanvasGraphics.context.canvas.width);
			} else {
				if(graphics.__canvas == null) {
					graphics.__canvas = window.document.createElement("canvas");
					graphics.__context = graphics.__canvas.getContext("2d");
				}
				openfl__$internal_renderer_canvas_CanvasGraphics.context = graphics.__context;
				graphics.__canvas.width = Math.ceil(openfl__$internal_renderer_canvas_CanvasGraphics.bounds.width);
				graphics.__canvas.height = Math.ceil(openfl__$internal_renderer_canvas_CanvasGraphics.bounds.height);
			}
			openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.clear();
			openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.clear();
			openfl__$internal_renderer_canvas_CanvasGraphics.hasFill = false;
			openfl__$internal_renderer_canvas_CanvasGraphics.hasStroke = false;
			openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill = null;
			openfl__$internal_renderer_canvas_CanvasGraphics.bitmapRepeat = false;
			var data = new openfl__$internal_renderer_DrawCommandReader(graphics.__commands);
			var _g = 0;
			var _g1 = graphics.__commands.types;
			try {
				while(_g < _g1.length) {
					var type = _g1[_g];
					++_g;
					switch(type[1]) {
					case 3:
						var c;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO;
						c = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.cubicCurveTo(c.buffer.f[c.fPos],c.buffer.f[c.fPos + 1],c.buffer.f[c.fPos + 3],c.buffer.f[c.fPos + 4],c.buffer.f[c.fPos + 5],c.buffer.f[c.fPos + 6]);
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.cubicCurveTo(c.buffer.f[c.fPos],c.buffer.f[c.fPos + 1],c.buffer.f[c.fPos + 3],c.buffer.f[c.fPos + 4],c.buffer.f[c.fPos + 5],c.buffer.f[c.fPos + 6]);
						break;
					case 4:
						var c1;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.CURVE_TO;
						c1 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.curveTo(c1.buffer.f[c1.fPos],c1.buffer.f[c1.fPos + 1],c1.buffer.f[c1.fPos + 2],c1.buffer.f[c1.fPos + 3]);
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.curveTo(c1.buffer.f[c1.fPos],c1.buffer.f[c1.fPos + 1],c1.buffer.f[c1.fPos + 2],c1.buffer.f[c1.fPos + 3]);
						break;
					case 16:
						var c2;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.LINE_TO;
						c2 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.lineTo(c2.buffer.f[c2.fPos],c2.buffer.f[c2.fPos + 1]);
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.lineTo(c2.buffer.f[c2.fPos],c2.buffer.f[c2.fPos + 1]);
						break;
					case 17:
						var c3;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.MOVE_TO;
						c3 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.moveTo(c3.buffer.f[c3.fPos],c3.buffer.f[c3.fPos + 1]);
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.moveTo(c3.buffer.f[c3.fPos],c3.buffer.f[c3.fPos + 1]);
						break;
					case 12:
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.END_FILL;
						data;
						openfl__$internal_renderer_canvas_CanvasGraphics.endFill();
						openfl__$internal_renderer_canvas_CanvasGraphics.endStroke();
						openfl__$internal_renderer_canvas_CanvasGraphics.hasFill = false;
						openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill = null;
						break;
					case 15:
						var c4;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.LINE_STYLE;
						c4 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.lineStyle(c4.buffer.o[c4.oPos],c4.buffer.o[c4.oPos + 1],c4.buffer.o[c4.oPos + 2],c4.buffer.o[c4.oPos + 3],c4.buffer.o[c4.oPos + 4],c4.buffer.o[c4.oPos + 5],c4.buffer.o[c4.oPos + 6],c4.buffer.o[c4.oPos + 7]);
						break;
					case 14:
						var c5;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.LINE_GRADIENT_STYLE;
						c5 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.lineGradientStyle(c5.buffer.o[c5.oPos],c5.buffer.ii[c5.iiPos],c5.buffer.ff[c5.ffPos],c5.buffer.ii[c5.iiPos + 1],c5.buffer.o[c5.oPos + 1],c5.buffer.o[c5.oPos + 2],c5.buffer.o[c5.oPos + 3],c5.buffer.o[c5.oPos + 4]);
						break;
					case 13:
						var c6;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.LINE_BITMAP_STYLE;
						c6 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.lineBitmapStyle(c6.buffer.o[c6.oPos],c6.buffer.o[c6.oPos + 1],c6.buffer.b[c6.bPos],c6.buffer.b[c6.bPos + 1]);
						break;
					case 0:case 1:case 2:
						openfl__$internal_renderer_canvas_CanvasGraphics.endFill();
						openfl__$internal_renderer_canvas_CanvasGraphics.endStroke();
						if(type == openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL) {
							var c7;
							data.advance();
							data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL;
							c7 = data;
							openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.beginBitmapFill(c7.buffer.o[c7.oPos],c7.buffer.o[c7.oPos + 1],c7.buffer.b[c7.bPos],c7.buffer.b[c7.bPos + 1]);
							openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.beginBitmapFill(c7.buffer.o[c7.oPos],c7.buffer.o[c7.oPos + 1],c7.buffer.b[c7.bPos],c7.buffer.b[c7.bPos + 1]);
						} else if(type == openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL) {
							var c8;
							data.advance();
							data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_GRADIENT_FILL;
							c8 = data;
							openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.beginGradientFill(c8.buffer.o[c8.oPos],c8.buffer.ii[c8.iiPos],c8.buffer.ff[c8.ffPos],c8.buffer.ii[c8.iiPos + 1],c8.buffer.o[c8.oPos + 1],c8.buffer.o[c8.oPos + 2],c8.buffer.o[c8.oPos + 3],c8.buffer.o[c8.oPos + 4]);
							openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.beginGradientFill(c8.buffer.o[c8.oPos],c8.buffer.ii[c8.iiPos],c8.buffer.ff[c8.ffPos],c8.buffer.ii[c8.iiPos + 1],c8.buffer.o[c8.oPos + 1],c8.buffer.o[c8.oPos + 2],c8.buffer.o[c8.oPos + 3],c8.buffer.o[c8.oPos + 4]);
						} else {
							var c9;
							data.advance();
							data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_FILL;
							c9 = data;
							openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.beginFill(c9.buffer.i[c9.iPos],c9.buffer.f[c9.fPos]);
							openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.beginFill(c9.buffer.i[c9.iPos],c9.buffer.f[c9.fPos]);
						}
						break;
					case 5:
						var c10;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE;
						c10 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.drawCircle(c10.buffer.f[c10.fPos],c10.buffer.f[c10.fPos + 1],c10.buffer.f[c10.fPos + 2]);
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.drawCircle(c10.buffer.f[c10.fPos],c10.buffer.f[c10.fPos + 1],c10.buffer.f[c10.fPos + 2]);
						break;
					case 6:
						var c11;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE;
						c11 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.drawEllipse(c11.buffer.f[c11.fPos],c11.buffer.f[c11.fPos + 1],c11.buffer.f[c11.fPos + 2],c11.buffer.f[c11.fPos + 3]);
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.drawEllipse(c11.buffer.f[c11.fPos],c11.buffer.f[c11.fPos + 1],c11.buffer.f[c11.fPos + 2],c11.buffer.f[c11.fPos + 3]);
						break;
					case 8:
						var c12;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_RECT;
						c12 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.drawRect(c12.buffer.f[c12.fPos],c12.buffer.f[c12.fPos + 1],c12.buffer.f[c12.fPos + 2],c12.buffer.f[c12.fPos + 3]);
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.drawRect(c12.buffer.f[c12.fPos],c12.buffer.f[c12.fPos + 1],c12.buffer.f[c12.fPos + 2],c12.buffer.f[c12.fPos + 3]);
						break;
					case 9:
						var c13;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT;
						c13 = data;
						openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.drawRoundRect(c13.buffer.f[c13.fPos],c13.buffer.f[c13.fPos + 1],c13.buffer.f[c13.fPos + 2],c13.buffer.f[c13.fPos + 3],c13.buffer.f[c13.fPos + 4],c13.buffer.f[c13.fPos + 5]);
						openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.drawRoundRect(c13.buffer.f[c13.fPos],c13.buffer.f[c13.fPos + 1],c13.buffer.f[c13.fPos + 2],c13.buffer.f[c13.fPos + 3],c13.buffer.f[c13.fPos + 4],c13.buffer.f[c13.fPos + 5]);
						break;
					case 11:
						openfl__$internal_renderer_canvas_CanvasGraphics.endFill();
						openfl__$internal_renderer_canvas_CanvasGraphics.endStroke();
						var c14;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_TRIANGLES;
						c14 = data;
						var v = c14.buffer.o[c14.oPos];
						var ind = c14.buffer.o[c14.oPos + 1];
						var uvt = c14.buffer.o[c14.oPos + 2];
						var pattern = null;
						var colorFill = openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill == null;
						if(colorFill && uvt != null) throw "__break__";
						if(!colorFill) {
							if(uvt == null) {
								var this1;
								this1 = new openfl_VectorData();
								var this2;
								this2 = new Array(0);
								this1.data = this2;
								this1.length = 0;
								this1.fixed = false;
								uvt = this1;
								var _g3 = 0;
								var _g2 = v.length / 2 | 0;
								while(_g3 < _g2) {
									var i1 = _g3++;
									if(!uvt.fixed) {
										uvt.length++;
										if(uvt.data.length < uvt.length) {
											var data1;
											var this3;
											this3 = new Array(uvt.data.length + 10);
											data1 = this3;
											haxe_ds__$Vector_Vector_$Impl_$.blit(uvt.data,0,data1,0,uvt.data.length);
											uvt.data = data1;
										}
										uvt.data[uvt.length - 1] = v.data[i1 * 2] / openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill.width;
									}
									uvt.length;
									if(!uvt.fixed) {
										uvt.length++;
										if(uvt.data.length < uvt.length) {
											var data2;
											var this4;
											this4 = new Array(uvt.data.length + 10);
											data2 = this4;
											haxe_ds__$Vector_Vector_$Impl_$.blit(uvt.data,0,data2,0,uvt.data.length);
											uvt.data = data2;
										}
										uvt.data[uvt.length - 1] = v.data[i1 * 2 + 1] / openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill.height;
									}
									uvt.length;
								}
							}
							var skipT = uvt.length != v.length;
							var normalizedUVT = openfl__$internal_renderer_canvas_CanvasGraphics.normalizeUVT(uvt,skipT);
							var maxUVT = normalizedUVT.max;
							uvt = normalizedUVT.uvt;
							if(maxUVT > 1) pattern = openfl__$internal_renderer_canvas_CanvasGraphics.createTempPatternCanvas(openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill,openfl__$internal_renderer_canvas_CanvasGraphics.bitmapRepeat,openfl__$internal_renderer_canvas_CanvasGraphics.bounds.width | 0,openfl__$internal_renderer_canvas_CanvasGraphics.bounds.height | 0); else pattern = openfl__$internal_renderer_canvas_CanvasGraphics.createTempPatternCanvas(openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill,openfl__$internal_renderer_canvas_CanvasGraphics.bitmapRepeat,openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill.width,openfl__$internal_renderer_canvas_CanvasGraphics.bitmapFill.height);
						}
						var i = 0;
						var l = ind.length;
						var a_;
						var b_;
						var c_;
						var iax;
						var iay;
						var ibx;
						var iby;
						var icx;
						var icy;
						var x1;
						var y1;
						var x2;
						var y2;
						var x3;
						var y3;
						var uvx1;
						var uvy1;
						var uvx2;
						var uvy2;
						var uvx3;
						var uvy3;
						var denom;
						var t1;
						var t2;
						var t3;
						var t4;
						var dx;
						var dy;
						while(i < l) {
							a_ = i;
							b_ = i + 1;
							c_ = i + 2;
							iax = ind.data[a_] * 2;
							iay = ind.data[a_] * 2 + 1;
							ibx = ind.data[b_] * 2;
							iby = ind.data[b_] * 2 + 1;
							icx = ind.data[c_] * 2;
							icy = ind.data[c_] * 2 + 1;
							x1 = v.data[iax];
							y1 = v.data[iay];
							x2 = v.data[ibx];
							y2 = v.data[iby];
							x3 = v.data[icx];
							y3 = v.data[icy];
							var _g21 = c14.buffer.o[c14.oPos + 3];
							switch(_g21[1]) {
							case 2:
								if(!((x2 - x1) * (y3 - y1) - (y2 - y1) * (x3 - x1) < 0)) {
									i += 3;
									continue;
								}
								break;
							case 0:
								if((x2 - x1) * (y3 - y1) - (y2 - y1) * (x3 - x1) < 0) {
									i += 3;
									continue;
								}
								break;
							default:
							}
							if(colorFill) {
								openfl__$internal_renderer_canvas_CanvasGraphics.context.beginPath();
								openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(x1,y1);
								openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(x2,y2);
								openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(x3,y3);
								openfl__$internal_renderer_canvas_CanvasGraphics.context.closePath();
								if(!openfl__$internal_renderer_canvas_CanvasGraphics.hitTesting) openfl__$internal_renderer_canvas_CanvasGraphics.context.fill();
								i += 3;
								continue;
							}
							openfl__$internal_renderer_canvas_CanvasGraphics.context.save();
							openfl__$internal_renderer_canvas_CanvasGraphics.context.beginPath();
							openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(x1,y1);
							openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(x2,y2);
							openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(x3,y3);
							openfl__$internal_renderer_canvas_CanvasGraphics.context.closePath();
							openfl__$internal_renderer_canvas_CanvasGraphics.context.clip();
							uvx1 = uvt.data[iax] * pattern.width;
							uvx2 = uvt.data[ibx] * pattern.width;
							uvx3 = uvt.data[icx] * pattern.width;
							uvy1 = uvt.data[iay] * pattern.height;
							uvy2 = uvt.data[iby] * pattern.height;
							uvy3 = uvt.data[icy] * pattern.height;
							denom = uvx1 * (uvy3 - uvy2) - uvx2 * uvy3 + uvx3 * uvy2 + (uvx2 - uvx3) * uvy1;
							if(denom == 0) {
								i += 3;
								continue;
							}
							t1 = -(uvy1 * (x3 - x2) - uvy2 * x3 + uvy3 * x2 + (uvy2 - uvy3) * x1) / denom;
							t2 = (uvy2 * y3 + uvy1 * (y2 - y3) - uvy3 * y2 + (uvy3 - uvy2) * y1) / denom;
							t3 = (uvx1 * (x3 - x2) - uvx2 * x3 + uvx3 * x2 + (uvx2 - uvx3) * x1) / denom;
							t4 = -(uvx2 * y3 + uvx1 * (y2 - y3) - uvx3 * y2 + (uvx3 - uvx2) * y1) / denom;
							dx = (uvx1 * (uvy3 * x2 - uvy2 * x3) + uvy1 * (uvx2 * x3 - uvx3 * x2) + (uvx3 * uvy2 - uvx2 * uvy3) * x1) / denom;
							dy = (uvx1 * (uvy3 * y2 - uvy2 * y3) + uvy1 * (uvx2 * y3 - uvx3 * y2) + (uvx3 * uvy2 - uvx2 * uvy3) * y1) / denom;
							openfl__$internal_renderer_canvas_CanvasGraphics.context.transform(t1,t2,t3,t4,dx,dy);
							openfl__$internal_renderer_canvas_CanvasGraphics.context.drawImage(pattern,0,0);
							openfl__$internal_renderer_canvas_CanvasGraphics.context.restore();
							i += 3;
						}
						break;
					case 10:
						var c15;
						data.advance();
						data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_TILES;
						c15 = data;
						var useScale = (c15.buffer.i[c15.iPos] & 1) > 0;
						var offsetX = openfl__$internal_renderer_canvas_CanvasGraphics.bounds.x;
						var offsetY = openfl__$internal_renderer_canvas_CanvasGraphics.bounds.y;
						var useRotation = (c15.buffer.i[c15.iPos] & 2) > 0;
						var useTransform = (c15.buffer.i[c15.iPos] & 16) > 0;
						var useRGB = (c15.buffer.i[c15.iPos] & 4) > 0;
						var useAlpha = (c15.buffer.i[c15.iPos] & 8) > 0;
						var useRect = (c15.buffer.i[c15.iPos] & 32) > 0;
						var useOrigin = (c15.buffer.i[c15.iPos] & 64) > 0;
						var useBlendAdd = (c15.buffer.i[c15.iPos] & 65536) > 0;
						if(useTransform) {
							useScale = false;
							useRotation = false;
						}
						var scaleIndex = 0;
						var rotationIndex = 0;
						var rgbIndex = 0;
						var alphaIndex = 0;
						var transformIndex = 0;
						var numValues = 3;
						if(useRect) if(useOrigin) numValues = 8; else numValues = 6;
						if(useScale) {
							scaleIndex = numValues;
							numValues++;
						}
						if(useRotation) {
							rotationIndex = numValues;
							numValues++;
						}
						if(useTransform) {
							transformIndex = numValues;
							numValues += 4;
						}
						if(useRGB) {
							rgbIndex = numValues;
							numValues += 3;
						}
						if(useAlpha) {
							alphaIndex = numValues;
							numValues++;
						}
						var totalCount = c15.buffer.ff[c15.ffPos].length;
						if(c15.buffer.i[c15.iPos + 1] >= 0 && totalCount > c15.buffer.i[c15.iPos + 1]) totalCount = c15.buffer.i[c15.iPos + 1];
						var itemCount = totalCount / numValues | 0;
						var index = 0;
						var rect = null;
						var center = null;
						var previousTileID = -1;
						var surface;
						c15.buffer.ts[c15.tsPos].__bitmap.__sync();
						surface = c15.buffer.ts[c15.tsPos].__bitmap.image.get_src();
						if(useBlendAdd) openfl__$internal_renderer_canvas_CanvasGraphics.context.globalCompositeOperation = "lighter";
						while(index < totalCount) {
							var tileID;
							if(!useRect) tileID = c15.buffer.ff[c15.ffPos][index + 2] | 0; else tileID = -1;
							if(!useRect && tileID != previousTileID) {
								rect = c15.buffer.ts[c15.tsPos].__tileRects[tileID];
								center = c15.buffer.ts[c15.tsPos].__centerPoints[tileID];
								previousTileID = tileID;
							} else if(useRect) {
								rect = c15.buffer.ts[c15.tsPos].__rectTile;
								rect.setTo(c15.buffer.ff[c15.ffPos][index + 2],c15.buffer.ff[c15.ffPos][index + 3],c15.buffer.ff[c15.ffPos][index + 4],c15.buffer.ff[c15.ffPos][index + 5]);
								center = c15.buffer.ts[c15.tsPos].__point;
								if(useOrigin) {
									center.x = c15.buffer.ff[c15.ffPos][index + 6];
									center.y = c15.buffer.ff[c15.ffPos][index + 7];
								} else {
									center.x = 0;
									center.y = 0;
								}
							}
							if(rect != null && rect.width > 0 && rect.height > 0 && center != null) {
								openfl__$internal_renderer_canvas_CanvasGraphics.context.save();
								openfl__$internal_renderer_canvas_CanvasGraphics.context.translate(c15.buffer.ff[c15.ffPos][index] - offsetX,c15.buffer.ff[c15.ffPos][index + 1] - offsetY);
								if(useRotation) openfl__$internal_renderer_canvas_CanvasGraphics.context.rotate(c15.buffer.ff[c15.ffPos][index + rotationIndex]);
								var scale = 1.0;
								if(useScale) scale = c15.buffer.ff[c15.ffPos][index + scaleIndex];
								if(useTransform) openfl__$internal_renderer_canvas_CanvasGraphics.context.transform(c15.buffer.ff[c15.ffPos][index + transformIndex],c15.buffer.ff[c15.ffPos][index + transformIndex + 1],c15.buffer.ff[c15.ffPos][index + transformIndex + 2],c15.buffer.ff[c15.ffPos][index + transformIndex + 3],0,0);
								if(useAlpha) openfl__$internal_renderer_canvas_CanvasGraphics.context.globalAlpha = c15.buffer.ff[c15.ffPos][index + alphaIndex];
								openfl__$internal_renderer_canvas_CanvasGraphics.context.drawImage(surface,rect.x,rect.y,rect.width,rect.height,-center.x * scale,-center.y * scale,rect.width * scale,rect.height * scale);
								openfl__$internal_renderer_canvas_CanvasGraphics.context.restore();
							}
							index += numValues;
						}
						if(useBlendAdd) openfl__$internal_renderer_canvas_CanvasGraphics.context.globalCompositeOperation = "source-over";
						break;
					default:
						data.advance();
						data.prev = type;
					}
				}
			} catch( e ) { if( e != "__break__" ) throw e; }
			if(openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands.get_length() > 0) openfl__$internal_renderer_canvas_CanvasGraphics.endFill();
			if(openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands.get_length() > 0) openfl__$internal_renderer_canvas_CanvasGraphics.endStroke();
			data.destroy();
			graphics.__bitmap = openfl_display_BitmapData.fromCanvas(graphics.__canvas);
		}
		graphics.set___dirty(false);
	}
};
openfl__$internal_renderer_canvas_CanvasGraphics.renderMask = function(graphics,renderSession) {
	if(graphics.__commands.get_length() != 0) {
		openfl__$internal_renderer_canvas_CanvasGraphics.context = renderSession.context;
		var positionX = 0.0;
		var positionY = 0.0;
		var offsetX = 0;
		var offsetY = 0;
		var data = new openfl__$internal_renderer_DrawCommandReader(graphics.__commands);
		var _g = 0;
		var _g1 = graphics.__commands.types;
		while(_g < _g1.length) {
			var type = _g1[_g];
			++_g;
			switch(type[1]) {
			case 3:
				var c;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO;
				c = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(c.buffer.f[c.fPos] - offsetX,c.buffer.f[c.fPos + 1] - offsetY,c.buffer.f[c.fPos + 3] - offsetX,c.buffer.f[c.fPos + 4] - offsetY,c.buffer.f[c.fPos + 5] - offsetX,c.buffer.f[c.fPos + 6] - offsetY);
				positionX = c.buffer.f[c.fPos + 5];
				positionY = c.buffer.f[c.fPos + 6];
				break;
			case 4:
				var c1;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CURVE_TO;
				c1 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.context.quadraticCurveTo(c1.buffer.f[c1.fPos] - offsetX,c1.buffer.f[c1.fPos + 1] - offsetY,c1.buffer.f[c1.fPos + 2] - offsetX,c1.buffer.f[c1.fPos + 3] - offsetY);
				positionX = c1.buffer.f[c1.fPos + 2];
				positionY = c1.buffer.f[c1.fPos + 3];
				break;
			case 5:
				var c2;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE;
				c2 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.context.arc(c2.buffer.f[c2.fPos] - offsetX,c2.buffer.f[c2.fPos + 1] - offsetY,c2.buffer.f[c2.fPos + 2],0,Math.PI * 2,true);
				break;
			case 6:
				var c3;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE;
				c3 = data;
				var x = c3.buffer.f[c3.fPos];
				var y = c3.buffer.f[c3.fPos + 1];
				var width = c3.buffer.f[c3.fPos + 2];
				var height = c3.buffer.f[c3.fPos + 3];
				x -= offsetX;
				y -= offsetY;
				var kappa = .5522848;
				var ox = width / 2 * kappa;
				var oy = height / 2 * kappa;
				var xe = x + width;
				var ye = y + height;
				var xm = x + width / 2;
				var ym = y + height / 2;
				openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(x,ym);
				openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(x,ym - oy,xm - ox,y,xm,y);
				openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(xm + ox,y,xe,ym - oy,xe,ym);
				openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(xe,ym + oy,xm + ox,ye,xm,ye);
				openfl__$internal_renderer_canvas_CanvasGraphics.context.bezierCurveTo(xm - ox,ye,x,ym + oy,x,ym);
				break;
			case 8:
				var c4;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_RECT;
				c4 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.context.rect(c4.buffer.f[c4.fPos] - offsetX,c4.buffer.f[c4.fPos + 1] - offsetY,c4.buffer.f[c4.fPos + 2],c4.buffer.f[c4.fPos + 3]);
				break;
			case 9:
				var c5;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT;
				c5 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.drawRoundRect(c5.buffer.f[c5.fPos] - offsetX,c5.buffer.f[c5.fPos + 1] - offsetY,c5.buffer.f[c5.fPos + 2],c5.buffer.f[c5.fPos + 3],c5.buffer.f[c5.fPos + 4],c5.buffer.f[c5.fPos + 5]);
				break;
			case 16:
				var c6;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_TO;
				c6 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.context.lineTo(c6.buffer.f[c6.fPos] - offsetX,c6.buffer.f[c6.fPos + 1] - offsetY);
				positionX = c6.buffer.f[c6.fPos];
				positionY = c6.buffer.f[c6.fPos + 1];
				break;
			case 17:
				var c7;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.MOVE_TO;
				c7 = data;
				openfl__$internal_renderer_canvas_CanvasGraphics.context.moveTo(c7.buffer.f[c7.fPos] - offsetX,c7.buffer.f[c7.fPos + 1] - offsetY);
				positionX = c7.buffer.f[c7.fPos];
				positionY = c7.buffer.f[c7.fPos + 1];
				break;
			default:
				data.advance();
				data.prev = type;
			}
		}
		data.destroy();
	}
};
var openfl__$internal_renderer_canvas_CanvasMaskManager = function(renderSession) {
	openfl__$internal_renderer_AbstractMaskManager.call(this,renderSession);
};
$hxClasses["openfl._internal.renderer.canvas.CanvasMaskManager"] = openfl__$internal_renderer_canvas_CanvasMaskManager;
openfl__$internal_renderer_canvas_CanvasMaskManager.__name__ = true;
openfl__$internal_renderer_canvas_CanvasMaskManager.__super__ = openfl__$internal_renderer_AbstractMaskManager;
openfl__$internal_renderer_canvas_CanvasMaskManager.prototype = $extend(openfl__$internal_renderer_AbstractMaskManager.prototype,{
	pushMask: function(mask) {
		var context = this.renderSession.context;
		context.save();
		var transform = mask.__getWorldTransform();
		context.setTransform(transform.a,transform.b,transform.c,transform.d,transform.tx,transform.ty);
		context.beginPath();
		mask.__renderCanvasMask(this.renderSession);
		context.clip();
	}
	,pushRect: function(rect,transform) {
		var context = this.renderSession.context;
		context.save();
		context.setTransform(transform.a,transform.b,transform.c,transform.d,transform.tx,transform.ty);
		context.beginPath();
		context.rect(rect.x,rect.y,rect.width,rect.height);
		context.clip();
	}
	,popMask: function() {
		this.renderSession.context.restore();
	}
	,popRect: function() {
		this.renderSession.context.restore();
	}
	,__class__: openfl__$internal_renderer_canvas_CanvasMaskManager
});
var openfl__$internal_renderer_canvas_CanvasRenderer = function(width,height,context) {
	openfl__$internal_renderer_AbstractRenderer.call(this,width,height);
	this.context = context;
	this.renderSession = new openfl__$internal_renderer_RenderSession();
	this.renderSession.context = context;
	this.renderSession.roundPixels = true;
	this.renderSession.renderer = this;
	this.renderSession.maskManager = new openfl__$internal_renderer_canvas_CanvasMaskManager(this.renderSession);
};
$hxClasses["openfl._internal.renderer.canvas.CanvasRenderer"] = openfl__$internal_renderer_canvas_CanvasRenderer;
openfl__$internal_renderer_canvas_CanvasRenderer.__name__ = true;
openfl__$internal_renderer_canvas_CanvasRenderer.__super__ = openfl__$internal_renderer_AbstractRenderer;
openfl__$internal_renderer_canvas_CanvasRenderer.prototype = $extend(openfl__$internal_renderer_AbstractRenderer.prototype,{
	render: function(stage) {
		this.context.setTransform(1,0,0,1,0,0);
		this.context.globalAlpha = 1;
		if(!stage.__transparent && stage.__clearBeforeRender) {
			this.context.fillStyle = stage.__colorString;
			this.context.fillRect(0,0,stage.stageWidth,stage.stageHeight);
		} else if(stage.__transparent && stage.__clearBeforeRender) this.context.clearRect(0,0,stage.stageWidth,stage.stageHeight);
		stage.__renderCanvas(this.renderSession);
	}
	,__class__: openfl__$internal_renderer_canvas_CanvasRenderer
});
var openfl__$internal_renderer_canvas_CanvasShape = function() { };
$hxClasses["openfl._internal.renderer.canvas.CanvasShape"] = openfl__$internal_renderer_canvas_CanvasShape;
openfl__$internal_renderer_canvas_CanvasShape.__name__ = true;
openfl__$internal_renderer_canvas_CanvasShape.render = function(shape,renderSession) {
	if(!shape.__renderable || shape.__worldAlpha <= 0) return;
	var graphics = shape.__graphics;
	if(graphics != null) {
		openfl__$internal_renderer_canvas_CanvasGraphics.render(graphics,renderSession);
		if(graphics.__canvas != null) {
			var context = renderSession.context;
			var scrollRect = shape.get_scrollRect();
			if(graphics.__bounds.width > 0 && graphics.__bounds.height > 0 && (scrollRect == null || scrollRect.width > 0 && scrollRect.height > 0)) {
				if(shape.__mask != null) renderSession.maskManager.pushMask(shape.__mask);
				context.globalAlpha = shape.__worldAlpha;
				var transform = shape.__worldTransform;
				if(renderSession.roundPixels) context.setTransform(transform.a,transform.b,transform.c,transform.d,transform.tx | 0,transform.ty | 0); else context.setTransform(transform.a,transform.b,transform.c,transform.d,transform.tx,transform.ty);
				if(scrollRect == null) context.drawImage(graphics.__canvas,graphics.__bounds.x,graphics.__bounds.y); else context.drawImage(graphics.__canvas,Math.ceil(graphics.__bounds.x + scrollRect.x),Math.ceil(graphics.__bounds.y + scrollRect.y),scrollRect.width,scrollRect.height,Math.ceil(graphics.__bounds.x + scrollRect.x),Math.ceil(graphics.__bounds.y + scrollRect.y),scrollRect.width,scrollRect.height);
				if(shape.__mask != null) renderSession.maskManager.popMask();
			}
		}
	}
};
var openfl__$internal_renderer_canvas_CanvasTextField = function() { };
$hxClasses["openfl._internal.renderer.canvas.CanvasTextField"] = openfl__$internal_renderer_canvas_CanvasTextField;
openfl__$internal_renderer_canvas_CanvasTextField.__name__ = true;
openfl__$internal_renderer_canvas_CanvasTextField.context = null;
openfl__$internal_renderer_canvas_CanvasTextField.render = function(textField,renderSession) {
	if(textField.__dirty) {
		var textEngine = textField.__textEngine;
		textField.__updateLayout();
		if((textEngine.text == null || textEngine.text == "") && !textEngine.background && !textEngine.border && !textEngine.__hasFocus || (textEngine.width <= 0 || textEngine.height <= 0) && textEngine.autoSize != openfl_text_TextFieldAutoSize.NONE) {
			textField.__graphics.__canvas = null;
			textField.__graphics.__context = null;
			textField.__graphics.set___dirty(false);
			textField.__dirty = false;
		} else {
			var bounds = textEngine.bounds;
			if(textField.__graphics == null || textField.__graphics.__canvas == null) {
				if(textField.__graphics == null) textField.__graphics = new openfl_display_Graphics();
				textField.__graphics.__canvas = window.document.createElement("canvas");
				textField.__graphics.__context = textField.__graphics.__canvas.getContext("2d");
				textField.__graphics.__bounds = new openfl_geom_Rectangle(0,0,bounds.width,bounds.height);
			}
			var graphics = textField.__graphics;
			openfl__$internal_renderer_canvas_CanvasTextField.context = graphics.__context;
			if(textEngine.text != null && textEngine.text != "" || textEngine.__hasFocus) {
				var text = textEngine.text;
				if(textEngine.displayAsPassword) {
					var length = text.length;
					var mask = "";
					var _g = 0;
					while(_g < length) {
						var i = _g++;
						mask += "*";
					}
					text = mask;
				}
				graphics.__canvas.width = Math.ceil(bounds.width);
				graphics.__canvas.height = Math.ceil(bounds.height);
				if(textEngine.antiAliasType != openfl_text_AntiAliasType.ADVANCED || textEngine.gridFitType != openfl_text_GridFitType.PIXEL) {
					graphics.__context.mozImageSmoothingEnabled = true;
					graphics.__context.msImageSmoothingEnabled = true;
					graphics.__context.imageSmoothingEnabled = true;
				} else {
					graphics.__context.mozImageSmoothingEnabled = false;
					graphics.__context.msImageSmoothingEnabled = false;
					graphics.__context.imageSmoothingEnabled = false;
				}
				if(textEngine.border || textEngine.background) {
					openfl__$internal_renderer_canvas_CanvasTextField.context.rect(0.5,0.5,bounds.width - 1,bounds.height - 1);
					if(textEngine.background) {
						openfl__$internal_renderer_canvas_CanvasTextField.context.fillStyle = "#" + StringTools.hex(textEngine.backgroundColor,6);
						openfl__$internal_renderer_canvas_CanvasTextField.context.fill();
					}
					if(textEngine.border) {
						openfl__$internal_renderer_canvas_CanvasTextField.context.lineWidth = 1;
						openfl__$internal_renderer_canvas_CanvasTextField.context.strokeStyle = "#" + StringTools.hex(textEngine.borderColor,6);
						openfl__$internal_renderer_canvas_CanvasTextField.context.stroke();
					}
				}
				openfl__$internal_renderer_canvas_CanvasTextField.context.textBaseline = "top";
				openfl__$internal_renderer_canvas_CanvasTextField.context.textAlign = "start";
				var scrollX = -textField.get_scrollH();
				var scrollY = 0.0;
				var _g1 = 0;
				var _g2 = textField.get_scrollV() - 1;
				while(_g1 < _g2) {
					var i1 = _g1++;
					scrollY -= textEngine.lineHeights[i1];
				}
				var advance;
				var offsetY = 0.0;
				var applyHack = new EReg("(iPad|iPhone|iPod|Firefox)","g").match(window.navigator.userAgent);
				var _g3 = 0;
				var _g11 = textEngine.layoutGroups;
				while(_g3 < _g11.length) {
					var group = _g11[_g3];
					++_g3;
					if(group.lineIndex < textField.get_scrollV() - 1) continue;
					if(group.lineIndex > textField.get_scrollV() + textEngine.bottomScrollV - 2) break;
					openfl__$internal_renderer_canvas_CanvasTextField.context.font = openfl__$internal_text_TextEngine.getFont(group.format);
					openfl__$internal_renderer_canvas_CanvasTextField.context.fillStyle = "#" + StringTools.hex(group.format.color,6);
					if(applyHack) offsetY = group.format.size * 0.185;
					openfl__$internal_renderer_canvas_CanvasTextField.context.fillText(text.substring(group.startIndex,group.endIndex),group.offsetX + scrollX,group.offsetY + offsetY + scrollY);
					if(textField.__caretIndex > -1 && textEngine.selectable) {
						if(textField.__selectionIndex == textField.__caretIndex) {
							if(textField.__showCursor && group.startIndex <= textField.__caretIndex && group.endIndex >= textField.__caretIndex) {
								advance = 0.0;
								var _g31 = 0;
								var _g21 = textField.__caretIndex - group.startIndex;
								while(_g31 < _g21) {
									var i2 = _g31++;
									if(group.advances.length <= i2) break;
									advance += group.advances[i2];
								}
								openfl__$internal_renderer_canvas_CanvasTextField.context.fillRect(group.offsetX + advance,group.offsetY,1,group.height);
							}
						} else if(group.startIndex <= textField.__caretIndex && group.endIndex >= textField.__caretIndex || group.startIndex <= textField.__selectionIndex && group.endIndex >= textField.__selectionIndex) {
							var selectionStart = Std["int"](Math.min(textField.__selectionIndex,textField.__caretIndex));
							var selectionEnd = Std["int"](Math.max(textField.__selectionIndex,textField.__caretIndex));
							if(group.startIndex > selectionStart) selectionStart = group.startIndex;
							if(group.endIndex < selectionEnd) selectionEnd = group.endIndex;
							var start;
							var end;
							start = textField.getCharBoundaries(selectionStart);
							if(selectionEnd >= textEngine.text.length) {
								end = textField.getCharBoundaries(textEngine.text.length - 1);
								end.x += end.width + 2;
							} else end = textField.getCharBoundaries(selectionEnd);
							if(start != null && end != null) {
								openfl__$internal_renderer_canvas_CanvasTextField.context.fillStyle = "#000000";
								openfl__$internal_renderer_canvas_CanvasTextField.context.fillRect(start.x,start.y,end.x - start.x,group.height);
								openfl__$internal_renderer_canvas_CanvasTextField.context.fillStyle = "#FFFFFF";
								openfl__$internal_renderer_canvas_CanvasTextField.context.fillText(text.substring(selectionStart,selectionEnd),scrollX + start.x,group.offsetY + offsetY + scrollY);
							}
						}
					}
				}
			} else {
				graphics.__canvas.width = Math.ceil(bounds.width);
				graphics.__canvas.height = Math.ceil(bounds.height);
				if(textEngine.border || textEngine.background) {
					if(textEngine.border) openfl__$internal_renderer_canvas_CanvasTextField.context.rect(0.5,0.5,bounds.width - 1,bounds.height - 1); else openfl__$internal_renderer_canvas_CanvasTextField.context.rect(0,0,bounds.width,bounds.height);
					if(textEngine.background) {
						openfl__$internal_renderer_canvas_CanvasTextField.context.fillStyle = "#" + StringTools.hex(textEngine.backgroundColor,6);
						openfl__$internal_renderer_canvas_CanvasTextField.context.fill();
					}
					if(textEngine.border) {
						openfl__$internal_renderer_canvas_CanvasTextField.context.lineWidth = 1;
						openfl__$internal_renderer_canvas_CanvasTextField.context.lineCap = "square";
						openfl__$internal_renderer_canvas_CanvasTextField.context.strokeStyle = "#" + StringTools.hex(textEngine.borderColor,6);
						openfl__$internal_renderer_canvas_CanvasTextField.context.stroke();
					}
				}
			}
			graphics.__bitmap = openfl_display_BitmapData.fromCanvas(textField.__graphics.__canvas);
			textField.__dirty = false;
			graphics.set___dirty(false);
		}
	}
};
var openfl__$internal_renderer_console_ConsoleRenderer = function(width,height,ctx) {
	openfl__$internal_renderer_AbstractRenderer.call(this,width,height);
	throw new js__$Boot_HaxeError("ConsoleRenderer not supported");
};
$hxClasses["openfl._internal.renderer.console.ConsoleRenderer"] = openfl__$internal_renderer_console_ConsoleRenderer;
openfl__$internal_renderer_console_ConsoleRenderer.__name__ = true;
openfl__$internal_renderer_console_ConsoleRenderer.__super__ = openfl__$internal_renderer_AbstractRenderer;
openfl__$internal_renderer_console_ConsoleRenderer.prototype = $extend(openfl__$internal_renderer_AbstractRenderer.prototype,{
	render: function(stage) {
	}
	,__class__: openfl__$internal_renderer_console_ConsoleRenderer
});
var openfl__$internal_renderer_dom_DOMBitmap = function() { };
$hxClasses["openfl._internal.renderer.dom.DOMBitmap"] = openfl__$internal_renderer_dom_DOMBitmap;
openfl__$internal_renderer_dom_DOMBitmap.__name__ = true;
openfl__$internal_renderer_dom_DOMBitmap.renderCanvas = function(bitmap,renderSession) {
	if(bitmap.__image != null) {
		renderSession.element.removeChild(bitmap.__image);
		bitmap.__image = null;
	}
	if(bitmap.__canvas == null) {
		bitmap.__canvas = window.document.createElement("canvas");
		bitmap.__context = bitmap.__canvas.getContext("2d");
		if(!bitmap.smoothing) {
			bitmap.__context.mozImageSmoothingEnabled = false;
			bitmap.__context.msImageSmoothingEnabled = false;
			bitmap.__context.imageSmoothingEnabled = false;
		}
		openfl__$internal_renderer_dom_DOMRenderer.initializeElement(bitmap,bitmap.__canvas,renderSession);
	}
	bitmap.bitmapData.__sync();
	bitmap.__canvas.width = bitmap.bitmapData.width;
	bitmap.__canvas.height = bitmap.bitmapData.height;
	bitmap.__context.globalAlpha = bitmap.__worldAlpha;
	bitmap.__context.drawImage(bitmap.bitmapData.image.buffer.__srcCanvas,0,0);
	openfl__$internal_renderer_dom_DOMRenderer.applyStyle(bitmap,renderSession,true,false,true);
};
openfl__$internal_renderer_dom_DOMBitmap.renderImage = function(bitmap,renderSession) {
	if(bitmap.__canvas != null) {
		renderSession.element.removeChild(bitmap.__canvas);
		bitmap.__canvas = null;
	}
	if(bitmap.__image == null) {
		bitmap.__image = window.document.createElement("img");
		bitmap.__image.src = bitmap.bitmapData.image.buffer.__srcImage.src;
		openfl__$internal_renderer_dom_DOMRenderer.initializeElement(bitmap,bitmap.__image,renderSession);
	}
	openfl__$internal_renderer_dom_DOMRenderer.applyStyle(bitmap,renderSession,true,true,true);
};
var openfl__$internal_renderer_dom_DOMMaskManager = function(renderSession) {
	openfl__$internal_renderer_AbstractMaskManager.call(this,renderSession);
};
$hxClasses["openfl._internal.renderer.dom.DOMMaskManager"] = openfl__$internal_renderer_dom_DOMMaskManager;
openfl__$internal_renderer_dom_DOMMaskManager.__name__ = true;
openfl__$internal_renderer_dom_DOMMaskManager.__super__ = openfl__$internal_renderer_AbstractMaskManager;
openfl__$internal_renderer_dom_DOMMaskManager.prototype = $extend(openfl__$internal_renderer_AbstractMaskManager.prototype,{
	pushMask: function(mask) {
	}
	,pushRect: function(rect,transform) {
	}
	,popMask: function() {
	}
	,__class__: openfl__$internal_renderer_dom_DOMMaskManager
});
var openfl__$internal_renderer_dom_DOMRenderer = function(width,height,element) {
	openfl__$internal_renderer_AbstractRenderer.call(this,width,height);
	this.element = element;
	this.renderSession = new openfl__$internal_renderer_RenderSession();
	this.renderSession.element = element;
	this.renderSession.roundPixels = true;
	var prefix = (function () {
		  var styles = window.getComputedStyle(document.documentElement, ''),
			pre = (Array.prototype.slice
			  .call(styles)
			  .join('') 
			  .match(/-(moz|webkit|ms)-/) || (styles.OLink === '' && ['', 'o'])
			)[1],
			dom = ('WebKit|Moz|MS|O').match(new RegExp('(' + pre + ')', 'i'))[1];
		  return {
			dom: dom,
			lowercase: pre,
			css: '-' + pre + '-',
			js: pre[0].toUpperCase() + pre.substr(1)
		  };
		})();
	this.renderSession.vendorPrefix = prefix.lowercase;
	if(prefix.lowercase == "webkit") this.renderSession.transformProperty = "-webkit-transform"; else this.renderSession.transformProperty = "transform";
	if(prefix.lowercase == "webkit") this.renderSession.transformOriginProperty = "-webkit-transform-origin"; else this.renderSession.transformOriginProperty = "transform-origin";
	this.renderSession.maskManager = new openfl__$internal_renderer_dom_DOMMaskManager(this.renderSession);
	this.renderSession.renderer = this;
};
$hxClasses["openfl._internal.renderer.dom.DOMRenderer"] = openfl__$internal_renderer_dom_DOMRenderer;
openfl__$internal_renderer_dom_DOMRenderer.__name__ = true;
openfl__$internal_renderer_dom_DOMRenderer.applyStyle = function(displayObject,renderSession,setTransform,setAlpha,setClip) {
	var style = displayObject.__style;
	if(setTransform && displayObject.__worldTransformChanged) style.setProperty(renderSession.transformProperty,displayObject.__worldTransform.to3DString(renderSession.roundPixels),null);
	if(displayObject.__worldZ != ++renderSession.z) {
		displayObject.__worldZ = renderSession.z;
		style.setProperty("z-index",displayObject.__worldZ == null?"null":"" + displayObject.__worldZ,null);
	}
	if(setAlpha && displayObject.__worldAlphaChanged) {
		if(displayObject.__worldAlpha < 1) style.setProperty("opacity",displayObject.__worldAlpha == null?"null":"" + displayObject.__worldAlpha,null); else style.removeProperty("opacity");
	}
	if(setClip && displayObject.__worldClipChanged) {
		if(displayObject.__worldClip == null) style.removeProperty("clip"); else {
			var clip = openfl_geom_Rectangle.__temp;
			var matrix = openfl_geom_Matrix.__temp;
			matrix.copyFrom(displayObject.__worldTransform);
			matrix.invert();
			displayObject.__worldClip.__transform(clip,matrix);
			style.setProperty("clip","rect(" + clip.y + "px, " + clip.get_right() + "px, " + clip.get_bottom() + "px, " + clip.x + "px)",null);
		}
	}
};
openfl__$internal_renderer_dom_DOMRenderer.initializeElement = function(displayObject,element,renderSession) {
	var style = displayObject.__style = element.style;
	style.setProperty("position","absolute",null);
	style.setProperty("top","0",null);
	style.setProperty("left","0",null);
	style.setProperty(renderSession.transformOriginProperty,"0 0 0",null);
	renderSession.element.appendChild(element);
	displayObject.__worldAlphaChanged = true;
	displayObject.__worldClipChanged = true;
	displayObject.__worldTransformChanged = true;
	displayObject.__worldVisibleChanged = true;
	displayObject.__worldZ = -1;
};
openfl__$internal_renderer_dom_DOMRenderer.__super__ = openfl__$internal_renderer_AbstractRenderer;
openfl__$internal_renderer_dom_DOMRenderer.prototype = $extend(openfl__$internal_renderer_AbstractRenderer.prototype,{
	render: function(stage) {
		this.element.style.background = stage.__colorString;
		this.renderSession.z = 1;
		stage.__renderDOM(this.renderSession);
	}
	,__class__: openfl__$internal_renderer_dom_DOMRenderer
});
var openfl__$internal_renderer_dom_DOMShape = function() { };
$hxClasses["openfl._internal.renderer.dom.DOMShape"] = openfl__$internal_renderer_dom_DOMShape;
openfl__$internal_renderer_dom_DOMShape.__name__ = true;
openfl__$internal_renderer_dom_DOMShape.render = function(shape,renderSession) {
	var graphics = shape.__graphics;
	if(shape.stage != null && shape.__worldVisible && shape.__renderable && graphics != null) {
		if(graphics.__dirty || shape.__worldAlphaChanged || shape.__canvas == null && graphics.__canvas != null) {
			openfl__$internal_renderer_canvas_CanvasGraphics.render(graphics,renderSession);
			if(graphics.__canvas != null) {
				if(shape.__canvas == null) {
					shape.__canvas = window.document.createElement("canvas");
					shape.__context = shape.__canvas.getContext("2d");
					openfl__$internal_renderer_dom_DOMRenderer.initializeElement(shape,shape.__canvas,renderSession);
				}
				shape.__canvas.width = graphics.__canvas.width;
				shape.__canvas.height = graphics.__canvas.height;
				shape.__context.globalAlpha = shape.__worldAlpha;
				shape.__context.drawImage(graphics.__canvas,0,0);
			} else if(shape.__canvas != null) {
				renderSession.element.removeChild(shape.__canvas);
				shape.__canvas = null;
				shape.__style = null;
			}
		}
		if(shape.__canvas != null) {
			if(shape.__worldTransformChanged || graphics.__transformDirty) {
				graphics.__transformDirty = false;
				var transform = openfl_geom_Matrix.__temp;
				transform.identity();
				transform.translate(graphics.__bounds.x,graphics.__bounds.y);
				transform.concat(shape.__worldTransform);
				shape.__style.setProperty(renderSession.transformProperty,renderSession.roundPixels?"matrix3d(" + transform.a + ", " + transform.b + ", " + "0, 0, " + transform.c + ", " + transform.d + ", " + "0, 0, 0, 0, 1, 0, " + (transform.tx | 0) + ", " + (transform.ty | 0) + ", 0, 1)":"matrix3d(" + transform.a + ", " + transform.b + ", " + "0, 0, " + transform.c + ", " + transform.d + ", " + "0, 0, 0, 0, 1, 0, " + transform.tx + ", " + transform.ty + ", 0, 1)",null);
			}
			openfl__$internal_renderer_dom_DOMRenderer.applyStyle(shape,renderSession,false,false,true);
		}
	} else if(shape.__canvas != null) {
		renderSession.element.removeChild(shape.__canvas);
		shape.__canvas = null;
		shape.__style = null;
	}
};
var openfl__$internal_renderer_dom_DOMTextField = function() { };
$hxClasses["openfl._internal.renderer.dom.DOMTextField"] = openfl__$internal_renderer_dom_DOMTextField;
openfl__$internal_renderer_dom_DOMTextField.__name__ = true;
openfl__$internal_renderer_dom_DOMTextField.render = function(textField,renderSession) {
	var textEngine = textField.__textEngine;
	if(textField.stage != null && textField.__worldVisible && textField.__renderable) {
		if(textField.__dirty || textField.__div == null) {
			if(textEngine.text != "" || textEngine.background || textEngine.border || textEngine.type == openfl_text_TextFieldType.INPUT) {
				if(textField.__div == null) {
					textField.__div = window.document.createElement("div");
					openfl__$internal_renderer_dom_DOMRenderer.initializeElement(textField,textField.__div,renderSession);
					textField.__style.setProperty("outline","none",null);
					textField.__div.addEventListener("input",function(event) {
						event.preventDefault();
						if(textField.get_htmlText() != textField.__div.innerHTML) {
							textField.set_htmlText(textField.__div.innerHTML);
							textField.__dirty = false;
						}
					},true);
				}
				if(textEngine.selectable) textField.__style.setProperty("cursor","text",null); else textField.__style.setProperty("cursor","inherit",null);
				textField.__div.contentEditable = textEngine.type == openfl_text_TextFieldType.INPUT;
				var style = textField.__style;
				textField.__div.innerHTML = textEngine.text;
				if(textEngine.background) style.setProperty("background-color","#" + StringTools.hex(textEngine.backgroundColor,6),null); else style.removeProperty("background-color");
				if(textEngine.border) style.setProperty("border","solid 1px #" + StringTools.hex(textEngine.borderColor,6),null); else style.removeProperty("border");
				style.setProperty("font",openfl__$internal_text_TextEngine.getFont(textField.__textFormat),null);
				style.setProperty("color","#" + StringTools.hex(textField.__textFormat.color,6),null);
				if(textEngine.autoSize != openfl_text_TextFieldAutoSize.NONE) style.setProperty("width","auto",null); else style.setProperty("width",textEngine.width + "px",null);
				style.setProperty("height",textEngine.height + "px",null);
				var _g = textField.__textFormat.align;
				switch(_g[1]) {
				case 3:
					style.setProperty("text-align","center",null);
					break;
				case 1:
					style.setProperty("text-align","right",null);
					break;
				default:
					style.setProperty("text-align","left",null);
				}
				textField.__dirty = false;
			} else if(textField.__div != null) {
				renderSession.element.removeChild(textField.__div);
				textField.__div = null;
			}
		}
		if(textField.__div != null) openfl__$internal_renderer_dom_DOMRenderer.applyStyle(textField,renderSession,true,true,false);
	} else if(textField.__div != null) {
		renderSession.element.removeChild(textField.__div);
		textField.__div = null;
		textField.__style = null;
	}
};
var openfl__$internal_renderer_opengl_GLBitmap = function() { };
$hxClasses["openfl._internal.renderer.opengl.GLBitmap"] = openfl__$internal_renderer_opengl_GLBitmap;
openfl__$internal_renderer_opengl_GLBitmap.__name__ = true;
openfl__$internal_renderer_opengl_GLBitmap.pushFramebuffer = function(renderSession,texture,viewPort,smoothing,transparent,clearBuffer,powerOfTwo) {
	if(powerOfTwo == null) powerOfTwo = true;
	if(clearBuffer == null) clearBuffer = false;
	if(transparent == null) transparent = true;
	var gl = renderSession.gl;
	if(gl == null) return null;
	var renderer = renderSession.renderer;
	var spritebatch = renderSession.spriteBatch;
	var x = viewPort.x | 0;
	var y = viewPort.y | 0;
	var width = viewPort.width | 0;
	var height = viewPort.height | 0;
	spritebatch.finish();
	if(openfl__$internal_renderer_opengl_GLBitmap.fbData.length <= 0) openfl__$internal_renderer_opengl_GLBitmap.fbData.push({ texture : null, viewPort : null, transparent : renderer.transparent});
	if(texture == null) texture = new openfl__$internal_renderer_opengl_utils_PingPongTexture(gl,width,height,smoothing,powerOfTwo);
	texture.resize(width,height);
	renderer.transparent = transparent;
	renderSession.maskManager.saveState();
	gl.bindFramebuffer(gl.FRAMEBUFFER,(texture.__swapped?texture.__texture1:texture.__texture0).frameBuffer);
	renderer.setViewport(x,y,width,height);
	gl.colorMask(true,true,true,true);
	renderSession.blendModeManager.setBlendMode(openfl_display_BlendMode.NORMAL);
	if(clearBuffer) (texture.__swapped?texture.__texture1:texture.__texture0).clear(0,0,0,0,null);
	openfl__$internal_renderer_opengl_GLBitmap.fbData.push({ texture : texture, viewPort : viewPort, transparent : transparent});
	return texture;
};
openfl__$internal_renderer_opengl_GLBitmap.drawBitmapDrawable = function(renderSession,target,source,matrix,colorTransform,blendMode,clipRect) {
	var data = openfl__$internal_renderer_opengl_GLBitmap.fbData[openfl__$internal_renderer_opengl_GLBitmap.fbData.length - 1];
	if(data == null) throw new js__$Boot_HaxeError("No data to draw to");
	var gl = renderSession.gl;
	if(gl == null) return;
	var viewPort = data.viewPort;
	var renderer = renderSession.renderer;
	var spritebatch = renderSession.spriteBatch;
	var drawTarget = target != null;
	var tmpRect;
	if(clipRect == null) tmpRect = new openfl_geom_Rectangle(viewPort.x,viewPort.y,viewPort.width,viewPort.height); else tmpRect = clipRect.clone();
	spritebatch.begin(renderSession,drawTarget?null:tmpRect);
	if(drawTarget) {
		target.__worldTransform.identity();
		openfl__$internal_renderer_opengl_GLBitmap.flipMatrix(target.__worldTransform,viewPort.height);
		target.__renderGL(renderSession);
		spritebatch.stop();
		if(target.__texture != null) gl.deleteTexture(target.__texture);
		target.__texture = null;
		spritebatch.start(tmpRect);
	}
	var ctCache = source.__worldColorTransform;
	var blendModeCache = source.__blendMode;
	var cached = source.__cacheAsBitmap;
	var m;
	if(matrix != null) m = new openfl_geom_Matrix(matrix.a,matrix.b,matrix.c,matrix.d,matrix.tx,matrix.ty); else m = new openfl_geom_Matrix();
	openfl__$internal_renderer_opengl_GLBitmap.flipMatrix(m,viewPort.height);
	if(colorTransform != null) source.__worldColorTransform = colorTransform; else source.__worldColorTransform = new openfl_geom_ColorTransform();
	source.__blendMode = blendMode;
	openfl_display_DisplayObject.__cacheAsBitmapMode = true;
	source.__updateTransforms(m);
	source.__updateChildren(false);
	source.__cacheAsBitmap = false;
	source.__renderGL(renderSession);
	source.__cacheAsBitmap = cached;
	source.__worldColorTransform = ctCache;
	source.__blendMode = blendModeCache;
	openfl_display_DisplayObject.__cacheAsBitmapMode = false;
	source.__updateTransforms();
	source.__updateChildren(false);
};
openfl__$internal_renderer_opengl_GLBitmap.popFramebuffer = function(renderSession,image) {
	var gl = renderSession.gl;
	if(gl == null) return;
	renderSession.spriteBatch.finish();
	openfl__$internal_renderer_opengl_GLBitmap.fbData.pop();
	var data = openfl__$internal_renderer_opengl_GLBitmap.fbData[openfl__$internal_renderer_opengl_GLBitmap.fbData.length - 1];
	if(data == null) throw new js__$Boot_HaxeError("oh");
	var x;
	var y;
	var width;
	var height;
	if(data.viewPort == null) {
		x = y = 0;
		width = renderSession.renderer.width;
		height = renderSession.renderer.height;
	} else {
		x = Math.floor(data.viewPort.x);
		y = Math.floor(data.viewPort.y);
		width = Math.ceil(data.viewPort.width);
		height = Math.ceil(data.viewPort.height);
	}
	if(image != null) {
		if(image.width != width || image.height != height) image.resize(width,height);
		gl.readPixels(x,y,width,height,gl.RGBA,gl.UNSIGNED_BYTE,image.buffer.data);
		image.dirty = false;
		image.set_premultiplied(true);
	}
	gl.bindFramebuffer(gl.FRAMEBUFFER,data.texture == null?renderSession.defaultFramebuffer:data.texture.get_framebuffer());
	renderSession.renderer.setViewport(x,y,width,height);
	renderSession.renderer.transparent = data.transparent;
	renderSession.maskManager.restoreState();
};
openfl__$internal_renderer_opengl_GLBitmap.flipMatrix = function(m,height) {
	var tx = m.tx;
	var ty = m.ty;
	m.tx = 0;
	m.ty = 0;
	m.scale(1,-1);
	m.translate(0,height);
	m.tx += tx;
	m.ty -= ty;
};
var openfl__$internal_renderer_opengl_GLRenderer = function(width,height,gl,transparent,antialias,preserveDrawingBuffer) {
	if(preserveDrawingBuffer == null) preserveDrawingBuffer = false;
	if(antialias == null) antialias = false;
	if(transparent == null) transparent = false;
	if(height == null) height = 600;
	if(width == null) width = 800;
	this.vpHeight = 0;
	this.vpWidth = 0;
	this.vpY = 0;
	this.vpX = 0;
	openfl__$internal_renderer_AbstractRenderer.call(this,width,height);
	this.transparent = transparent;
	this.preserveDrawingBuffer = preserveDrawingBuffer;
	this.width = width;
	this.height = height;
	this.viewport = new openfl_geom_Rectangle();
	this.options = { alpha : transparent, antialias : antialias, premultipliedAlpha : transparent, stencil : true, preserveDrawingBuffer : preserveDrawingBuffer};
	this._glContextId = openfl__$internal_renderer_opengl_GLRenderer.glContextId++;
	this.gl = gl;
	this.defaultFramebuffer = null;
	openfl__$internal_renderer_opengl_GLRenderer.glContexts[this._glContextId] = gl;
	this.projectionMatrix = new openfl_geom_Matrix();
	this.projection = new openfl_geom_Point();
	this.projection.x = this.width / 2;
	this.projection.y = -this.height / 2;
	this.offset = new openfl_geom_Point(0,0);
	this.resize(this.width,this.height);
	this.contextLost = false;
	this.shaderManager = new openfl__$internal_renderer_opengl_utils_ShaderManager(gl);
	this.spriteBatch = new openfl__$internal_renderer_opengl_utils_SpriteBatch(gl);
	this.filterManager = new openfl__$internal_renderer_opengl_utils_FilterManager(gl,this.transparent);
	this.stencilManager = new openfl__$internal_renderer_opengl_utils_StencilManager(gl);
	this.blendModeManager = new openfl__$internal_renderer_opengl_utils_BlendModeManager(gl);
	this.renderSession = new openfl__$internal_renderer_RenderSession();
	this.renderSession.gl = this.gl;
	this.renderSession.drawCount = 0;
	this.renderSession.shaderManager = this.shaderManager;
	this.renderSession.filterManager = this.filterManager;
	this.renderSession.blendModeManager = this.blendModeManager;
	this.renderSession.spriteBatch = this.spriteBatch;
	this.renderSession.stencilManager = this.stencilManager;
	this.renderSession.renderer = this;
	this.renderSession.defaultFramebuffer = this.defaultFramebuffer;
	this.renderSession.projectionMatrix = this.projectionMatrix;
	this.maskManager = new openfl__$internal_renderer_opengl_utils_GLMaskManager(this.renderSession);
	this.renderSession.maskManager = this.maskManager;
	this.shaderManager.setShader(this.shaderManager.defaultShader);
	gl.disable(gl.DEPTH_TEST);
	gl.disable(gl.CULL_FACE);
	gl.enable(gl.BLEND);
	gl.colorMask(true,true,true,this.transparent);
};
$hxClasses["openfl._internal.renderer.opengl.GLRenderer"] = openfl__$internal_renderer_opengl_GLRenderer;
openfl__$internal_renderer_opengl_GLRenderer.__name__ = true;
openfl__$internal_renderer_opengl_GLRenderer.renderBitmap = function(shape,renderSession,smooth) {
	if(smooth == null) smooth = true;
	if(!shape.__renderable || shape.__worldAlpha <= 0) return;
	if(shape.__graphics == null || shape.__graphics.__bitmap == null) return;
	var rect = openfl_geom_Rectangle.__temp;
	var matrix = openfl_geom_Matrix.__temp;
	rect.setEmpty();
	matrix.identity();
	shape.__getBounds(rect,matrix);
	var bitmap = shape.__graphics.__bitmap;
	matrix.translate(shape.__graphics.__bounds.x,shape.__graphics.__bounds.y);
	matrix.concat(shape.__renderTransform);
	renderSession.spriteBatch.renderBitmapData(bitmap,smooth,matrix,shape.__worldColorTransform,shape.__worldAlpha,shape.__blendMode,null,openfl_display_PixelSnapping.ALWAYS);
};
openfl__$internal_renderer_opengl_GLRenderer.__super__ = openfl__$internal_renderer_AbstractRenderer;
openfl__$internal_renderer_opengl_GLRenderer.prototype = $extend(openfl__$internal_renderer_AbstractRenderer.prototype,{
	setViewport: function(x,y,width,height) {
		if(!(this.vpX == x && this.vpY == y && this.vpWidth == width && this.vpHeight == height)) {
			this.vpX = x;
			this.vpY = y;
			this.vpWidth = width;
			this.vpHeight = height;
			this.gl.viewport(x,y,width,height);
			this.setOrtho(x,y,width,height);
			this.viewport.setTo(x,y,width,height);
		}
	}
	,setOrtho: function(x,y,width,height) {
		var o = this.projectionMatrix;
		o.identity();
		o.a = 1 / width * 2;
		o.d = -1 / height * 2;
		o.tx = -1 - x * o.a;
		o.ty = 1 - y * o.d;
	}
	,render: function(stage) {
		if(this.contextLost) return;
		var gl = this.gl;
		this.setViewport(0,0,this.width,this.height);
		gl.bindFramebuffer(gl.FRAMEBUFFER,this.defaultFramebuffer);
		if(this.transparent) gl.clearColor(0,0,0,0); else gl.clearColor(stage.__colorSplit[0],stage.__colorSplit[1],stage.__colorSplit[2],1);
		gl.clear(gl.COLOR_BUFFER_BIT);
		this.renderDisplayObject(stage,this.projection);
	}
	,renderDisplayObject: function(displayObject,projection,buffer) {
		this.renderSession.blendModeManager.setBlendMode(openfl_display_BlendMode.NORMAL);
		this.renderSession.drawCount = 0;
		this.renderSession.currentBlendMode = null;
		this.spriteBatch.begin(this.renderSession);
		this.filterManager.begin(this.renderSession,buffer);
		displayObject.__renderGL(this.renderSession);
		this.spriteBatch.finish();
	}
	,resize: function(width,height) {
		this.width = width;
		this.height = height;
		openfl__$internal_renderer_AbstractRenderer.prototype.resize.call(this,width,height);
		this.setViewport(0,0,width,height);
		this.projection.x = width / 2;
		this.projection.y = -height / 2;
	}
	,__class__: openfl__$internal_renderer_opengl_GLRenderer
});
var openfl__$internal_renderer_opengl_shaders2_Shader = function(gl) {
	this.wrapT = 33071;
	this.wrapS = 33071;
	this.compiled = false;
	this.uniforms = new haxe_ds_StringMap();
	this.attributes = new haxe_ds_StringMap();
	this.ID = openfl__$internal_renderer_opengl_shaders2_Shader.UID++;
	this.gl = gl;
	this.program = null;
};
$hxClasses["openfl._internal.renderer.opengl.shaders2.Shader"] = openfl__$internal_renderer_opengl_shaders2_Shader;
openfl__$internal_renderer_opengl_shaders2_Shader.__name__ = true;
openfl__$internal_renderer_opengl_shaders2_Shader.compileProgram = function(gl,vertexSrc,fragmentSrc) {
	var cache = openfl__$internal_renderer_opengl_utils_ShaderManager.compiledShadersCache;
	var key = haxe_crypto_Md5.encode(vertexSrc + fragmentSrc);
	if(__map_reserved[key] != null?cache.existsReserved(key):cache.h.hasOwnProperty(key)) return __map_reserved[key] != null?cache.getReserved(key):cache.h[key];
	var vertexShader = openfl__$internal_renderer_opengl_shaders2_Shader.compileShader(gl,vertexSrc,gl.VERTEX_SHADER);
	var fragmentShader = openfl__$internal_renderer_opengl_shaders2_Shader.compileShader(gl,fragmentSrc,gl.FRAGMENT_SHADER);
	var program = gl.createProgram();
	if(vertexShader != null && fragmentShader != null) {
		gl.attachShader(program,vertexShader);
		gl.attachShader(program,fragmentShader);
		gl.linkProgram(program);
		gl.deleteShader(vertexShader);
		gl.deleteShader(fragmentShader);
		if(gl.getProgramParameter(program,gl.LINK_STATUS) == 0) {
			console.log("Could not compile the program:\n\t" + gl.getProgramInfoLog(program));
			console.log("VERTEX:\n" + vertexSrc + "\nFRAGMENT:\n" + fragmentSrc);
			return null;
		}
	}
	if(__map_reserved[key] != null) cache.setReserved(key,program); else cache.h[key] = program;
	return program;
};
openfl__$internal_renderer_opengl_shaders2_Shader.compileShader = function(gl,shaderSrc,type) {
	var src = shaderSrc;
	var shader = gl.createShader(type);
	gl.shaderSource(shader,src);
	gl.compileShader(shader);
	if(gl.getShaderParameter(shader,gl.COMPILE_STATUS) == 0) {
		console.log("Could not compile the shader:\n\t" + gl.getShaderInfoLog(shader));
		console.log(shaderSrc);
		return null;
	}
	return shader;
};
openfl__$internal_renderer_opengl_shaders2_Shader.prototype = {
	init: function(force) {
		if(force == null) force = false;
		if(this.compiled && !force) return;
		if(this.vertexSrc != null) this.vertexString = this.vertexSrc.join("\n");
		if(this.fragmentSrc != null) this.fragmentString = this.fragmentSrc.join("\n");
		if(this.vertexString == null || this.fragmentString == null) throw new js__$Boot_HaxeError("No vertex or fragment source provided");
		this.program = openfl__$internal_renderer_opengl_shaders2_Shader.compileProgram(this.gl,this.vertexString,this.fragmentString);
		if(this.program != null) this.compiled = true;
	}
	,destroy: function() {
		if(this.program != null) this.gl.deleteProgram(this.program);
		this.compiled = false;
		this.attributes = null;
	}
	,applyData: function(shaderData,renderSession) {
		if(shaderData == null) return;
		var param;
		var u;
		var v;
		var bd;
		var $it0 = shaderData.keys();
		while( $it0.hasNext() ) {
			var key = $it0.next();
			u = this.getUniformLocation(key);
			param = __map_reserved[key] != null?shaderData.getReserved(key):shaderData.h[key];
			if(param == null) continue;
			v = param.value;
			bd = param.bitmap;
			if(v == null && bd == null) continue;
			var _g = param.internalType;
			switch(_g) {
			case 1:
				var _g1 = param.size;
				switch(_g1) {
				case 1:
					this.gl.uniform1i(u,v[0] | 0);
					break;
				case 2:
					this.gl.uniform2i(u,v[0] | 0,v[1] | 0);
					break;
				case 3:
					this.gl.uniform3i(u,v[0] | 0,v[1] | 0,v[2] | 0);
					break;
				case 4:
					this.gl.uniform4i(u,v[0] | 0,v[1] | 0,v[2] | 0,v[3] | 0);
					break;
				}
				break;
			case 2:
				var _g11 = param.size;
				switch(_g11) {
				case 1:
					this.gl.uniform1f(u,v[0]);
					break;
				case 2:
					this.gl.uniform2f(u,v[0],v[1]);
					break;
				case 3:
					this.gl.uniform3f(u,v[0],v[1],v[2]);
					break;
				case 4:
					this.gl.uniform4f(u,v[0],v[1],v[2],v[3]);
					break;
				}
				break;
			case 3:
				var _g12 = param.size;
				switch(_g12) {
				case 2:
					this.gl.uniformMatrix2fv(u,param.transpose,(function($this) {
						var $r;
						var array = param.value;
						var this1;
						if(array != null) this1 = new Float32Array(array); else this1 = null;
						$r = this1;
						return $r;
					}(this)));
					break;
				case 3:
					this.gl.uniformMatrix3fv(u,param.transpose,(function($this) {
						var $r;
						var array1 = param.value;
						var this2;
						if(array1 != null) this2 = new Float32Array(array1); else this2 = null;
						$r = this2;
						return $r;
					}(this)));
					break;
				case 4:
					this.gl.uniformMatrix4fv(u,param.transpose,(function($this) {
						var $r;
						var array2 = param.value;
						var this3;
						if(array2 != null) this3 = new Float32Array(array2); else this3 = null;
						$r = this3;
						return $r;
					}(this)));
					break;
				}
				break;
			case 4:
				if(bd == null || !bd.__isValid) continue;
				this.gl.activeTexture(this.gl.TEXTURE0 + renderSession.activeTextures);
				this.gl.bindTexture(this.gl.TEXTURE_2D,bd.getTexture(this.gl));
				this.gl.uniform1i(u,renderSession.activeTextures);
				this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_MAG_FILTER,param.smooth?this.gl.LINEAR:this.gl.NEAREST);
				this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_MIN_FILTER,param.smooth?this.gl.LINEAR:this.gl.NEAREST);
				this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_WRAP_S,param.repeatX);
				this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_WRAP_T,param.repeatY);
				renderSession.activeTextures++;
				break;
			default:
			}
		}
	}
	,getAttribLocation: function(attribute) {
		if(this.program == null) throw new js__$Boot_HaxeError("Shader isn't initialized");
		if(this.attributes.exists(attribute)) return this.attributes.get(attribute); else {
			var location = this.gl.getAttribLocation(this.program,attribute);
			this.attributes.set(attribute,location);
			return location;
		}
	}
	,getUniformLocation: function(uniform) {
		if(this.program == null) throw new js__$Boot_HaxeError("Shader isn't initialized");
		if(this.uniforms.exists(uniform)) return this.uniforms.get(uniform); else {
			var location = this.gl.getUniformLocation(this.program,uniform);
			this.uniforms.set(uniform,location);
			return location;
		}
	}
	,enableVertexAttribute: function(attribute,stride,offset) {
		var location = this.getAttribLocation(attribute.name);
		this.gl.enableVertexAttribArray(location);
		this.gl.vertexAttribPointer(location,attribute.components,attribute.type,attribute.normalized,stride,offset * 4);
	}
	,disableVertexAttribute: function(attribute,setDefault) {
		if(setDefault == null) setDefault = true;
		var location = this.getAttribLocation(attribute.name);
		this.gl.disableVertexAttribArray(location);
		if(setDefault) {
			var _g = attribute.components;
			switch(_g) {
			case 1:
				this.gl.vertexAttrib1fv(location,attribute.defaultValue.subarray(0,1));
				break;
			case 2:
				this.gl.vertexAttrib2fv(location,attribute.defaultValue.subarray(0,2));
				break;
			case 3:
				this.gl.vertexAttrib3fv(location,attribute.defaultValue.subarray(0,3));
				break;
			default:
				this.gl.vertexAttrib4fv(location,attribute.defaultValue.subarray(0,4));
			}
		}
	}
	,bindVertexArray: function(va) {
		var offset = 0;
		var stride = va.get_stride();
		var _g = 0;
		var _g1 = va.attributes;
		while(_g < _g1.length) {
			var attribute = _g1[_g];
			++_g;
			if(attribute.enabled) {
				this.enableVertexAttribute(attribute,stride,offset);
				offset += Math.floor(attribute.components * attribute.getElementsBytes() / 4);
			} else this.disableVertexAttribute(attribute,true);
		}
	}
	,__class__: openfl__$internal_renderer_opengl_shaders2_Shader
};
var openfl__$internal_renderer_opengl_shaders2_DefaultShader = function(gl) {
	openfl__$internal_renderer_opengl_shaders2_Shader.call(this,gl);
	this.vertexSrc = openfl__$internal_renderer_opengl_shaders2_DefaultShader.VERTEX_SRC;
	this.fragmentSrc = ["#ifdef GL_ES","precision lowp float;","#endif","uniform sampler2D " + "openfl_uSampler0" + ";","uniform vec4 " + "openfl_uColorMultiplier" + ";","uniform vec4 " + "openfl_uColorOffset" + ";","uniform bool " + "openfl_uUseColorTransform" + ";","varying vec2 " + "openfl_vTexCoord" + ";","varying vec4 " + "openfl_vColor" + ";","vec4 colorTransform(const vec4 color, const vec4 tint, const vec4 multiplier, const vec4 offset) {","\tif(!" + "openfl_uUseColorTransform" + ") {","\t\treturn color * tint;","\t}","\tvec4 unmultiply;","\tif (color.a == 0.0) {","\t\tunmultiply = vec4(0.0, 0.0, 0.0, 0.0);","\t} else {","   \tunmultiply = vec4(color.rgb / color.a, color.a);","\t}","   vec4 result = unmultiply * tint * multiplier;","   result = result + offset;","   result = clamp(result, 0., 1.);","   result = vec4(result.rgb * result.a, result.a);","   return result;","}","void main(void) {","   vec4 tc = texture2D(" + "openfl_uSampler0" + ", " + "openfl_vTexCoord" + ");","   gl_FragColor = colorTransform(tc, " + "openfl_vColor" + ", " + "openfl_uColorMultiplier" + ", " + "openfl_uColorOffset" + ");","}"];
	this.init();
};
$hxClasses["openfl._internal.renderer.opengl.shaders2.DefaultShader"] = openfl__$internal_renderer_opengl_shaders2_DefaultShader;
openfl__$internal_renderer_opengl_shaders2_DefaultShader.__name__ = true;
openfl__$internal_renderer_opengl_shaders2_DefaultShader.__super__ = openfl__$internal_renderer_opengl_shaders2_Shader;
openfl__$internal_renderer_opengl_shaders2_DefaultShader.prototype = $extend(openfl__$internal_renderer_opengl_shaders2_Shader.prototype,{
	init: function(force) {
		if(force == null) force = false;
		openfl__$internal_renderer_opengl_shaders2_Shader.prototype.init.call(this,force);
		this.getAttribLocation("openfl_aPosition");
		this.getAttribLocation("openfl_aTexCoord0");
		this.getAttribLocation("openfl_aColor");
		this.getUniformLocation("openfl_uProjectionMatrix");
		this.getUniformLocation("openfl_uSampler0");
		this.getUniformLocation("openfl_uColorMultiplier");
		this.getUniformLocation("openfl_uColorOffset");
		this.getUniformLocation("openfl_uUseColorTransform");
	}
	,__class__: openfl__$internal_renderer_opengl_shaders2_DefaultShader
});
var openfl__$internal_renderer_opengl_shaders2_DrawTrianglesShader = function(gl) {
	openfl__$internal_renderer_opengl_shaders2_Shader.call(this,gl);
	this.vertexSrc = ["attribute vec2 " + "openfl_aPosition" + ";","attribute vec2 " + "openfl_aTexCoord0" + ";","attribute vec4 " + "openfl_aColor" + ";","uniform mat3 " + "openfl_uProjectionMatrix" + ";","varying vec2 vTexCoord;","varying vec4 vColor;","void main(void) {","   gl_Position = vec4((" + "openfl_uProjectionMatrix" + " * vec3(" + "openfl_aPosition" + ", 1.0)).xy, 0.0, 1.0);","   vTexCoord = " + "openfl_aTexCoord0" + ";","   vColor = " + "openfl_aColor" + ".bgra;","}"];
	this.fragmentSrc = ["#ifdef GL_ES","precision lowp float;","#endif","uniform sampler2D " + "openfl_uSampler0" + ";","uniform vec3 " + "openfl_uColor" + ";","uniform bool " + "openfl_uUseTexture" + ";","uniform float " + "openfl_uAlpha" + ";","uniform vec4 " + "openfl_uColorMultiplier" + ";","uniform vec4 " + "openfl_uColorOffset" + ";","varying vec2 vTexCoord;","varying vec4 vColor;","vec4 tmp;","vec4 colorTransform(const vec4 color, const vec4 tint, const vec4 multiplier, const vec4 offset) {","   vec4 unmultiply = vec4(color.rgb / color.a, color.a);","   vec4 result = unmultiply * tint * multiplier;","   result = result + offset;","   result = clamp(result, 0., 1.);","   result = vec4(result.rgb * result.a, result.a);","   return result;","}","void main(void) {","   if(" + "openfl_uUseTexture" + ") {","       tmp = texture2D(" + "openfl_uSampler0" + ", vTexCoord);","   } else {","       tmp = vec4(" + "openfl_uColor" + ", 1.);","   }","   gl_FragColor = colorTransform(tmp, vColor, " + "openfl_uColorMultiplier" + ", " + "openfl_uColorOffset" + ");","}"];
	this.init();
};
$hxClasses["openfl._internal.renderer.opengl.shaders2.DrawTrianglesShader"] = openfl__$internal_renderer_opengl_shaders2_DrawTrianglesShader;
openfl__$internal_renderer_opengl_shaders2_DrawTrianglesShader.__name__ = true;
openfl__$internal_renderer_opengl_shaders2_DrawTrianglesShader.__super__ = openfl__$internal_renderer_opengl_shaders2_Shader;
openfl__$internal_renderer_opengl_shaders2_DrawTrianglesShader.prototype = $extend(openfl__$internal_renderer_opengl_shaders2_Shader.prototype,{
	init: function(force) {
		if(force == null) force = false;
		openfl__$internal_renderer_opengl_shaders2_Shader.prototype.init.call(this,force);
		this.getAttribLocation("openfl_aPosition");
		this.getAttribLocation("openfl_aTexCoord0");
		this.getAttribLocation("openfl_aColor");
		this.getUniformLocation("openfl_uSampler0");
		this.getUniformLocation("openfl_uProjectionMatrix");
		this.getUniformLocation("openfl_uColor");
		this.getUniformLocation("openfl_uAlpha");
		this.getUniformLocation("openfl_uUseTexture");
		this.getUniformLocation("openfl_uColorMultiplier");
		this.getUniformLocation("openfl_uColorOffset");
	}
	,__class__: openfl__$internal_renderer_opengl_shaders2_DrawTrianglesShader
});
var openfl__$internal_renderer_opengl_shaders2_FillShader = function(gl) {
	openfl__$internal_renderer_opengl_shaders2_Shader.call(this,gl);
	this.vertexSrc = ["attribute vec2 " + "openfl_aPosition" + ";","uniform mat3 " + "openfl_uTranslationMatrix" + ";","uniform mat3 " + "openfl_uProjectionMatrix" + ";","uniform vec4 " + "openfl_uColor" + ";","uniform float " + "openfl_uAlpha" + ";","uniform vec4 " + "openfl_uColorMultiplier" + ";","uniform vec4 " + "openfl_uColorOffset" + ";","varying vec4 vColor;","vec4 colorTransform(const vec4 color, const float alpha, const vec4 multiplier, const vec4 offset) {","   vec4 result = color * multiplier;","   result.a *= alpha;","   result = result + offset;","   result = clamp(result, 0., 1.);","   result = vec4(result.rgb * result.a, result.a);","   return result;","}","void main(void) {","   gl_Position = vec4((" + "openfl_uProjectionMatrix" + " * " + "openfl_uTranslationMatrix" + " * vec3(" + "openfl_aPosition" + ", 1.0)).xy, 0.0, 1.0);","   vColor = colorTransform(" + "openfl_uColor" + ", " + "openfl_uAlpha" + ", " + "openfl_uColorMultiplier" + ", " + "openfl_uColorOffset" + ");","}"];
	this.fragmentSrc = ["#ifdef GL_ES","precision lowp float;","#endif","varying vec4 vColor;","void main(void) {","   gl_FragColor = vColor;","}"];
	this.init();
};
$hxClasses["openfl._internal.renderer.opengl.shaders2.FillShader"] = openfl__$internal_renderer_opengl_shaders2_FillShader;
openfl__$internal_renderer_opengl_shaders2_FillShader.__name__ = true;
openfl__$internal_renderer_opengl_shaders2_FillShader.__super__ = openfl__$internal_renderer_opengl_shaders2_Shader;
openfl__$internal_renderer_opengl_shaders2_FillShader.prototype = $extend(openfl__$internal_renderer_opengl_shaders2_Shader.prototype,{
	init: function(force) {
		if(force == null) force = false;
		openfl__$internal_renderer_opengl_shaders2_Shader.prototype.init.call(this,force);
		this.getAttribLocation("openfl_aPosition");
		this.getUniformLocation("openfl_uTranslationMatrix");
		this.getUniformLocation("openfl_uProjectionMatrix");
		this.getUniformLocation("openfl_uColor");
		this.getUniformLocation("openfl_uColorMultiplier");
		this.getUniformLocation("openfl_uColorOffset");
	}
	,__class__: openfl__$internal_renderer_opengl_shaders2_FillShader
});
var openfl__$internal_renderer_opengl_shaders2_PatternFillShader = function(gl) {
	openfl__$internal_renderer_opengl_shaders2_Shader.call(this,gl);
	this.vertexSrc = ["attribute vec2 " + "openfl_aPosition" + ";","uniform mat3 " + "openfl_uTranslationMatrix" + ";","uniform mat3 " + "openfl_uProjectionMatrix" + ";","uniform mat3 " + "openfl_uPatternMatrix" + ";","varying vec2 vPosition;","void main(void) {","   gl_Position = vec4((" + "openfl_uProjectionMatrix" + " * " + "openfl_uTranslationMatrix" + " * vec3(" + "openfl_aPosition" + ", 1.0)).xy, 0.0, 1.0);","   vPosition = (" + "openfl_uPatternMatrix" + " * vec3(" + "openfl_aPosition" + ", 1)).xy;","}"];
	this.fragmentSrc = ["#ifdef GL_ES","precision lowp float;","#endif","uniform float " + "openfl_uAlpha" + ";","uniform vec2 " + "openfl_uPatternTL" + ";","uniform vec2 " + "openfl_uPatternBR" + ";","uniform sampler2D " + "openfl_uSampler0" + ";","uniform vec4 " + "openfl_uColorMultiplier" + ";","uniform vec4 " + "openfl_uColorOffset" + ";","varying vec2 vPosition;","vec4 colorTransform(const vec4 color, const float alpha, const vec4 multiplier, const vec4 offset) {","   vec4 unmultiply = vec4(color.rgb / color.a, color.a);","   vec4 result = unmultiply * multiplier;","   result.a *= alpha;","   result = result + offset;","   result = clamp(result, 0., 1.);","   result = vec4(result.rgb * result.a, result.a);","   return result;","}","void main(void) {","   vec2 pos = mix(" + "openfl_uPatternTL" + ", " + "openfl_uPatternBR" + ", vPosition);","   vec4 tcol = texture2D(" + "openfl_uSampler0" + ", pos);","   gl_FragColor = colorTransform(tcol, " + "openfl_uAlpha" + ", " + "openfl_uColorMultiplier" + ", " + "openfl_uColorOffset" + ");","}"];
	this.init();
};
$hxClasses["openfl._internal.renderer.opengl.shaders2.PatternFillShader"] = openfl__$internal_renderer_opengl_shaders2_PatternFillShader;
openfl__$internal_renderer_opengl_shaders2_PatternFillShader.__name__ = true;
openfl__$internal_renderer_opengl_shaders2_PatternFillShader.__super__ = openfl__$internal_renderer_opengl_shaders2_Shader;
openfl__$internal_renderer_opengl_shaders2_PatternFillShader.prototype = $extend(openfl__$internal_renderer_opengl_shaders2_Shader.prototype,{
	init: function(force) {
		if(force == null) force = false;
		openfl__$internal_renderer_opengl_shaders2_Shader.prototype.init.call(this,force);
		this.getAttribLocation("openfl_aPosition");
		this.getUniformLocation("openfl_uTranslationMatrix");
		this.getUniformLocation("openfl_uPatternMatrix");
		this.getUniformLocation("openfl_uProjectionMatrix");
		this.getUniformLocation("openfl_uSampler0");
		this.getUniformLocation("openfl_uPatternTL");
		this.getUniformLocation("openfl_uPatternBR");
		this.getUniformLocation("openfl_uAlpha");
		this.getUniformLocation("openfl_uColorMultiplier");
		this.getUniformLocation("openfl_uColorOffset");
	}
	,__class__: openfl__$internal_renderer_opengl_shaders2_PatternFillShader
});
var openfl__$internal_renderer_opengl_shaders2_PrimitiveShader = function(gl) {
	openfl__$internal_renderer_opengl_shaders2_Shader.call(this,gl);
	this.vertexSrc = ["attribute vec2 " + "openfl_aPosition" + ";","attribute vec4 " + "openfl_aColor" + ";","uniform mat3 " + "openfl_uTranslationMatrix" + ";","uniform mat3 " + "openfl_uProjectionMatrix" + ";","uniform vec4 " + "openfl_uColorMultiplier" + ";","uniform vec4 " + "openfl_uColorOffset" + ";","uniform float " + "openfl_uAlpha" + ";","varying vec4 vColor;","vec4 colorTransform(const vec4 color, const float alpha, const vec4 multiplier, const vec4 offset) {","   vec4 result = color * multiplier;","   result.a *= alpha;","   result = result + offset;","   result = clamp(result, 0., 1.);","   result = vec4(result.rgb * result.a, result.a);","   return result;","}","void main(void) {","   gl_Position = vec4((" + "openfl_uProjectionMatrix" + " * " + "openfl_uTranslationMatrix" + " * vec3(" + "openfl_aPosition" + ", 1.0)).xy, 0.0, 1.0);","   vColor = colorTransform(" + "openfl_aColor" + ", " + "openfl_uAlpha" + ", " + "openfl_uColorMultiplier" + ", " + "openfl_uColorOffset" + ");","}"];
	this.fragmentSrc = ["#ifdef GL_ES","precision lowp float;","#endif","varying vec4 vColor;","void main(void) {","   gl_FragColor = vColor;","}"];
	this.init();
};
$hxClasses["openfl._internal.renderer.opengl.shaders2.PrimitiveShader"] = openfl__$internal_renderer_opengl_shaders2_PrimitiveShader;
openfl__$internal_renderer_opengl_shaders2_PrimitiveShader.__name__ = true;
openfl__$internal_renderer_opengl_shaders2_PrimitiveShader.__super__ = openfl__$internal_renderer_opengl_shaders2_Shader;
openfl__$internal_renderer_opengl_shaders2_PrimitiveShader.prototype = $extend(openfl__$internal_renderer_opengl_shaders2_Shader.prototype,{
	init: function(force) {
		if(force == null) force = false;
		openfl__$internal_renderer_opengl_shaders2_Shader.prototype.init.call(this,force);
		this.getAttribLocation("openfl_aPosition");
		this.getAttribLocation("openfl_aColor");
		this.getUniformLocation("openfl_uTranslationMatrix");
		this.getUniformLocation("openfl_uProjectionMatrix");
		this.getUniformLocation("openfl_uAlpha");
		this.getUniformLocation("openfl_uColorMultiplier");
		this.getUniformLocation("openfl_uColorOffset");
	}
	,__class__: openfl__$internal_renderer_opengl_shaders2_PrimitiveShader
});
var openfl__$internal_renderer_opengl_utils_BlendModeManager = function(gl) {
	this.gl = gl;
	this.currentBlendMode = null;
};
$hxClasses["openfl._internal.renderer.opengl.utils.BlendModeManager"] = openfl__$internal_renderer_opengl_utils_BlendModeManager;
openfl__$internal_renderer_opengl_utils_BlendModeManager.__name__ = true;
openfl__$internal_renderer_opengl_utils_BlendModeManager.prototype = {
	setBlendMode: function(blendMode,force) {
		if(force == null) force = false;
		if(blendMode == null) {
			blendMode = openfl_display_BlendMode.NORMAL;
			force = true;
		}
		if(!force && this.currentBlendMode == blendMode) return false;
		this.currentBlendMode = blendMode;
		switch(blendMode[1]) {
		case 0:
			this.gl.blendEquation(32774);
			this.gl.blendFunc(1,1);
			break;
		case 9:
			this.gl.blendEquation(32774);
			this.gl.blendFunc(774,771);
			break;
		case 12:
			this.gl.blendEquation(32774);
			this.gl.blendFunc(1,769);
			break;
		case 13:
			this.gl.blendEquation(32779);
			this.gl.blendFunc(1,1);
			break;
		default:
			this.gl.blendEquation(32774);
			this.gl.blendFunc(1,771);
		}
		return true;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_BlendModeManager
};
var openfl__$internal_renderer_opengl_utils_DrawPath = function(makeArray) {
	if(makeArray == null) makeArray = true;
	this.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
	this.points = null;
	this.winding = 0;
	this.isRemovable = true;
	this.fillIndex = 0;
	this.line = new openfl__$internal_renderer_opengl_utils_LineStyle();
	this.fill = openfl__$internal_renderer_opengl_utils_FillType.None;
	if(makeArray) this.points = [];
};
$hxClasses["openfl._internal.renderer.opengl.utils.DrawPath"] = openfl__$internal_renderer_opengl_utils_DrawPath;
openfl__$internal_renderer_opengl_utils_DrawPath.__name__ = true;
openfl__$internal_renderer_opengl_utils_DrawPath.getStack = function(graphics,gl) {
	return openfl__$internal_renderer_opengl_utils_PathBuiler.build(graphics,gl);
};
openfl__$internal_renderer_opengl_utils_DrawPath.prototype = {
	update: function(line,fill,fillIndex,winding) {
		this.updateLine(line);
		this.fill = fill;
		this.fillIndex = fillIndex;
		this.winding = winding;
	}
	,updateLine: function(line) {
		this.line.width = line.width;
		this.line.color = line.color;
		if(line.alpha == null) this.line.alpha = 1; else this.line.alpha = line.alpha;
		if(line.scaleMode == null) this.line.scaleMode = openfl_display_LineScaleMode.NORMAL; else this.line.scaleMode = line.scaleMode;
		if(line.caps == null) this.line.caps = openfl_display_CapsStyle.ROUND; else this.line.caps = line.caps;
		if(line.joints == null) this.line.joints = openfl_display_JointStyle.ROUND; else this.line.joints = line.joints;
		this.line.miterLimit = line.miterLimit;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_DrawPath
};
var openfl__$internal_renderer_opengl_utils_PathBuiler = function() { };
$hxClasses["openfl._internal.renderer.opengl.utils.PathBuiler"] = openfl__$internal_renderer_opengl_utils_PathBuiler;
openfl__$internal_renderer_opengl_utils_PathBuiler.__name__ = true;
openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = null;
openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths = null;
openfl__$internal_renderer_opengl_utils_PathBuiler.__line = null;
openfl__$internal_renderer_opengl_utils_PathBuiler.__fill = null;
openfl__$internal_renderer_opengl_utils_PathBuiler.closePath = function() {
	var l;
	if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null) l = 0; else l = openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length;
	if(l <= 0) return;
	if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type == openfl__$internal_renderer_opengl_utils_GraphicType.Polygon && openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.fill != openfl__$internal_renderer_opengl_utils_FillType.None) {
		var sx = openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points[0];
		var sy = openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points[1];
		var ex = openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points[l - 2];
		var ey = openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points[l - 1];
		if(!(sx == ex && sy == ey)) openfl__$internal_renderer_opengl_utils_PathBuiler.lineTo(sx,sy);
	}
};
openfl__$internal_renderer_opengl_utils_PathBuiler.endFill = function() {
	openfl__$internal_renderer_opengl_utils_PathBuiler.__fill = openfl__$internal_renderer_opengl_utils_FillType.None;
	openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex++;
};
openfl__$internal_renderer_opengl_utils_PathBuiler.lineTo = function(x,y) {
	var points = openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points;
	var push_point = true;
	if(points.length > 1) {
		var lastX = points[points.length - 2];
		var lastY = points[points.length - 1];
		if(lastX == x && lastY == y) push_point = false;
	}
	if(push_point == true) {
		openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(x);
		openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(y);
	}
};
openfl__$internal_renderer_opengl_utils_PathBuiler.build = function(graphics,gl) {
	var glStack = null;
	var bounds = graphics.__bounds;
	openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths = [];
	openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
	openfl__$internal_renderer_opengl_utils_PathBuiler.__line = new openfl__$internal_renderer_opengl_utils_LineStyle();
	openfl__$internal_renderer_opengl_utils_PathBuiler.__fill = openfl__$internal_renderer_opengl_utils_FillType.None;
	openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex = 0;
	glStack = graphics.__glStack[openfl__$internal_renderer_opengl_GLRenderer.glContextId];
	if(glStack == null) glStack = graphics.__glStack[openfl__$internal_renderer_opengl_GLRenderer.glContextId] = new openfl__$internal_renderer_opengl_utils_GLStack(gl);
	if(!graphics.__visible || graphics.__commands.get_length() == 0 || bounds == null || bounds.width == 0 || bounds.height == 0) {
	} else {
		var data = new openfl__$internal_renderer_DrawCommandReader(graphics.__commands);
		var _g = 0;
		var _g1 = graphics.__commands.types;
		while(_g < _g1.length) {
			var type = _g1[_g];
			++_g;
			switch(type[1]) {
			case 0:
				var c;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_BITMAP_FILL;
				c = data;
				openfl__$internal_renderer_opengl_utils_PathBuiler.endFill();
				if(c.buffer.o[c.oPos] != null) openfl__$internal_renderer_opengl_utils_PathBuiler.__fill = openfl__$internal_renderer_opengl_utils_FillType.Texture(c.buffer.o[c.oPos],c.buffer.o[c.oPos + 1],c.buffer.b[c.bPos],c.buffer.b[c.bPos + 1]); else openfl__$internal_renderer_opengl_utils_PathBuiler.__fill = openfl__$internal_renderer_opengl_utils_FillType.None;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0) {
					if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points = [];
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
					openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				}
				break;
			case 1:
				var c1;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.BEGIN_FILL;
				c1 = data;
				openfl__$internal_renderer_opengl_utils_PathBuiler.endFill();
				if(c1.buffer.f[c1.fPos] > 0) openfl__$internal_renderer_opengl_utils_PathBuiler.__fill = openfl__$internal_renderer_opengl_utils_FillType.Color(c1.buffer.i[c1.iPos] & 16777215,c1.buffer.f[c1.fPos]); else openfl__$internal_renderer_opengl_utils_PathBuiler.__fill = openfl__$internal_renderer_opengl_utils_FillType.None;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0) {
					if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points = [];
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
					openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				}
				break;
			case 3:
				var c2;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CUBIC_CURVE_TO;
				c2 = data;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0) {
					if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(0);
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(0);
					openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				}
				openfl__$internal_renderer_GraphicsPaths.cubicCurveTo(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points,c2.buffer.f[c2.fPos],c2.buffer.f[c2.fPos + 1],c2.buffer.f[c2.fPos + 3],c2.buffer.f[c2.fPos + 4],c2.buffer.f[c2.fPos + 5],c2.buffer.f[c2.fPos + 6]);
				break;
			case 4:
				var c3;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.CURVE_TO;
				c3 = data;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0) {
					if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(0);
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(0);
					openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				}
				openfl__$internal_renderer_GraphicsPaths.curveTo(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points,c3.buffer.f[c3.fPos],c3.buffer.f[c3.fPos + 1],c3.buffer.f[c3.fPos + 2],c3.buffer.f[c3.fPos + 3]);
				break;
			case 5:
				var c4;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_CIRCLE;
				c4 = data;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Circle;
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points = [c4.buffer.f[c4.fPos],c4.buffer.f[c4.fPos + 1],c4.buffer.f[c4.fPos + 2]];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				break;
			case 6:
				var c5;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ELLIPSE;
				c5 = data;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Ellipse;
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points = [c5.buffer.f[c5.fPos],c5.buffer.f[c5.fPos + 1],c5.buffer.f[c5.fPos + 2],c5.buffer.f[c5.fPos + 3]];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				break;
			case 8:
				var c6;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_RECT;
				c6 = data;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Rectangle(false);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points = [c6.buffer.f[c6.fPos],c6.buffer.f[c6.fPos + 1],c6.buffer.f[c6.fPos + 2],c6.buffer.f[c6.fPos + 3]];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				break;
			case 9:
				var c7;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_ROUND_RECT;
				c7 = data;
				var x = c7.buffer.f[c7.fPos];
				var y = c7.buffer.f[c7.fPos + 1];
				var width = c7.buffer.f[c7.fPos + 2];
				var height = c7.buffer.f[c7.fPos + 3];
				var rx = c7.buffer.f[c7.fPos + 4];
				var ry = c7.buffer.f[c7.fPos + 5];
				if(ry == -1) ry = rx;
				rx *= 0.5;
				ry *= 0.5;
				if(rx > width / 2) rx = width / 2;
				if(ry > height / 2) ry = height / 2;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Rectangle(true);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points = [x,y,width,height,rx,ry];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				break;
			case 12:
				var c8;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.END_FILL;
				c8 = data;
				openfl__$internal_renderer_opengl_utils_PathBuiler.endFill();
				break;
			case 15:
				var c9;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_STYLE;
				c9 = data;
				openfl__$internal_renderer_opengl_utils_PathBuiler.__line = new openfl__$internal_renderer_opengl_utils_LineStyle();
				if(c9.buffer.o[c9.oPos] == null || isNaN(c9.buffer.o[c9.oPos]) || c9.buffer.o[c9.oPos] < 0) openfl__$internal_renderer_opengl_utils_PathBuiler.__line.width = 0; else if(c9.buffer.o[c9.oPos] == 0) openfl__$internal_renderer_opengl_utils_PathBuiler.__line.width = 1; else openfl__$internal_renderer_opengl_utils_PathBuiler.__line.width = c9.buffer.o[c9.oPos];
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				if(c9.buffer.o[c9.oPos + 1] == null) openfl__$internal_renderer_opengl_utils_PathBuiler.__line.color = 0; else openfl__$internal_renderer_opengl_utils_PathBuiler.__line.color = c9.buffer.o[c9.oPos + 1];
				if(c9.buffer.o[c9.oPos + 2] == null) openfl__$internal_renderer_opengl_utils_PathBuiler.__line.alpha = 1; else openfl__$internal_renderer_opengl_utils_PathBuiler.__line.alpha = c9.buffer.o[c9.oPos + 2];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__line.scaleMode = c9.buffer.o[c9.oPos + 4];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__line.caps = c9.buffer.o[c9.oPos + 5];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__line.joints = c9.buffer.o[c9.oPos + 6];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__line.miterLimit = c9.buffer.o[c9.oPos + 7];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points = [];
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
				openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				break;
			case 16:
				var c10;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.LINE_TO;
				c10 = data;
				openfl__$internal_renderer_opengl_utils_PathBuiler.lineTo(c10.buffer.f[c10.fPos],c10.buffer.f[c10.fPos + 1]);
				break;
			case 17:
				var c11;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.MOVE_TO;
				c11 = data;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(c11.buffer.f[c11.fPos]);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(c11.buffer.f[c11.fPos + 1]);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				break;
			case 11:
				var c12;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_TRIANGLES;
				c12 = data;
				var uvtData = c12.buffer.o[c12.oPos + 2];
				var vertices = c12.buffer.o[c12.oPos];
				var indices = c12.buffer.o[c12.oPos + 1];
				var culling = c12.buffer.o[c12.oPos + 3];
				var colors = c12.buffer.o[c12.oPos + 4];
				var blendMode = c12.buffer.i[c12.iPos];
				var isColor;
				{
					var _g2 = openfl__$internal_renderer_opengl_utils_PathBuiler.__fill;
					switch(_g2[1]) {
					case 1:
						isColor = true;
						break;
					default:
						isColor = false;
					}
				}
				if(isColor && uvtData != null) continue;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
				if(uvtData == null) {
					var this1;
					this1 = new openfl_VectorData();
					var this2;
					this2 = new Array(0);
					this1.data = this2;
					this1.length = 0;
					this1.fixed = false;
					uvtData = this1;
					{
						var _g21 = openfl__$internal_renderer_opengl_utils_PathBuiler.__fill;
						switch(_g21[1]) {
						case 2:
							var b = _g21[2];
							var _g4 = 0;
							var _g3 = vertices.length / 2 | 0;
							while(_g4 < _g3) {
								var i = _g4++;
								if(!uvtData.fixed) {
									uvtData.length++;
									if(uvtData.data.length < uvtData.length) {
										var data1;
										var this3;
										this3 = new Array(uvtData.data.length + 10);
										data1 = this3;
										haxe_ds__$Vector_Vector_$Impl_$.blit(uvtData.data,0,data1,0,uvtData.data.length);
										uvtData.data = data1;
									}
									uvtData.data[uvtData.length - 1] = vertices.data[i * 2] / b.width;
								}
								uvtData.length;
								if(!uvtData.fixed) {
									uvtData.length++;
									if(uvtData.data.length < uvtData.length) {
										var data2;
										var this4;
										this4 = new Array(uvtData.data.length + 10);
										data2 = this4;
										haxe_ds__$Vector_Vector_$Impl_$.blit(uvtData.data,0,data2,0,uvtData.data.length);
										uvtData.data = data2;
									}
									uvtData.data[uvtData.length - 1] = vertices.data[i * 2 + 1] / b.height;
								}
								uvtData.length;
							}
							break;
						default:
						}
					}
				}
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.DrawTriangles(vertices,indices,uvtData,culling,colors,blendMode);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable = false;
				openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				break;
			case 10:
				var c13;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_TILES;
				c13 = data;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex++;
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath(false);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.DrawTiles(c13.buffer.ts[c13.tsPos],c13.buffer.ff[c13.ffPos],c13.buffer.b[c13.bPos],c13.buffer.i[c13.iPos],c13.buffer.o[c13.oPos],c13.buffer.i[c13.iPos + 1]);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable = false;
				openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				break;
			case 7:
				var c14;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.DRAW_PATH;
				c14 = data;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				switch(c14.buffer.o[c14.oPos + 2]) {
				case openfl_display_GraphicsPathWinding.EVEN_ODD:
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding = 0;
					break;
				case openfl_display_GraphicsPathWinding.NON_ZERO:
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding = 1;
					break;
				default:
					openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding = 0;
				}
				var command;
				var cx;
				var cy;
				var cx2;
				var cy2;
				var ax;
				var ay;
				var idx = 0;
				var _g31 = 0;
				var _g22 = c14.buffer.o[c14.oPos].length;
				while(_g31 < _g22) {
					var i1 = _g31++;
					command = c14.buffer.o[c14.oPos].data[i1];
					switch(command) {
					case 1:
						ax = c14.buffer.o[c14.oPos + 1].data[idx];
						ay = c14.buffer.o[c14.oPos + 1].data[idx + 1];
						idx += 2;
						if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(ax);
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(ay);
						openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
						break;
					case 4:
						ax = c14.buffer.o[c14.oPos + 1].data[idx + 2];
						ay = c14.buffer.o[c14.oPos + 1].data[idx + 3];
						idx += 4;
						if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(ax);
						openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(ay);
						openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
						break;
					case 2:
						ax = c14.buffer.o[c14.oPos + 1].data[idx];
						ay = c14.buffer.o[c14.oPos + 1].data[idx + 1];
						idx += 2;
						openfl__$internal_renderer_opengl_utils_PathBuiler.lineTo(ax,ay);
						break;
					case 5:
						ax = c14.buffer.o[c14.oPos + 1].data[idx + 2];
						ay = c14.buffer.o[c14.oPos + 1].data[idx + 3];
						idx += 4;
						openfl__$internal_renderer_opengl_utils_PathBuiler.lineTo(ax,ay);
						break;
					case 3:
						cx = c14.buffer.o[c14.oPos + 1].data[idx];
						cy = c14.buffer.o[c14.oPos + 1].data[idx + 1];
						ax = c14.buffer.o[c14.oPos + 1].data[idx + 2];
						ay = c14.buffer.o[c14.oPos + 1].data[idx + 3];
						idx += 4;
						if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0) {
							if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(0);
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(0);
							openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
						}
						openfl__$internal_renderer_GraphicsPaths.curveTo(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points,cx,cy,ax,ay);
						break;
					case 6:
						cx = c14.buffer.o[c14.oPos + 1].data[idx];
						cy = c14.buffer.o[c14.oPos + 1].data[idx + 1];
						cx2 = c14.buffer.o[c14.oPos + 1].data[idx + 2];
						cy2 = c14.buffer.o[c14.oPos + 1].data[idx + 3];
						ax = c14.buffer.o[c14.oPos + 1].data[idx + 4];
						ay = c14.buffer.o[c14.oPos + 1].data[idx + 5];
						idx += 6;
						if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0) {
							if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(0);
							openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.push(0);
							openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
						}
						openfl__$internal_renderer_GraphicsPaths.cubicCurveTo(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points,cx,cy,cx2,cy2,ax,ay);
						break;
					default:
					}
				}
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding = 0;
				break;
			case 18:
				var c15;
				data.advance();
				data.prev = openfl__$internal_renderer_DrawCommandType.OVERRIDE_MATRIX;
				c15 = data;
				if(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable && (openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points == null || openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.points.length == 0)) openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.pop(); else openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath = new openfl__$internal_renderer_opengl_utils_DrawPath();
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.update(openfl__$internal_renderer_opengl_utils_PathBuiler.__line,openfl__$internal_renderer_opengl_utils_PathBuiler.__fill,openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex,openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.type = openfl__$internal_renderer_opengl_utils_GraphicType.OverrideMatrix(c15.buffer.o[c15.oPos]);
				openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath.isRemovable = false;
				openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths.push(openfl__$internal_renderer_opengl_utils_PathBuiler.__currentPath);
				break;
			default:
				data.advance();
				data.prev = type;
			}
		}
		openfl__$internal_renderer_opengl_utils_PathBuiler.closePath();
		data.destroy();
	}
	graphics.__drawPaths = openfl__$internal_renderer_opengl_utils_PathBuiler.__drawPaths;
	return glStack;
};
var openfl__$internal_renderer_opengl_utils_LineStyle = function() {
	this.width = 0;
	this.color = 0;
	this.alpha = 1;
	this.scaleMode = openfl_display_LineScaleMode.NORMAL;
	this.caps = openfl_display_CapsStyle.ROUND;
	this.joints = openfl_display_JointStyle.ROUND;
	this.miterLimit = 3;
};
$hxClasses["openfl._internal.renderer.opengl.utils.LineStyle"] = openfl__$internal_renderer_opengl_utils_LineStyle;
openfl__$internal_renderer_opengl_utils_LineStyle.__name__ = true;
openfl__$internal_renderer_opengl_utils_LineStyle.prototype = {
	__class__: openfl__$internal_renderer_opengl_utils_LineStyle
};
var openfl__$internal_renderer_opengl_utils_FillType = $hxClasses["openfl._internal.renderer.opengl.utils.FillType"] = { __ename__ : true, __constructs__ : ["None","Color","Texture","Gradient"] };
openfl__$internal_renderer_opengl_utils_FillType.None = ["None",0];
openfl__$internal_renderer_opengl_utils_FillType.None.toString = $estr;
openfl__$internal_renderer_opengl_utils_FillType.None.__enum__ = openfl__$internal_renderer_opengl_utils_FillType;
openfl__$internal_renderer_opengl_utils_FillType.Color = function(color,alpha) { var $x = ["Color",1,color,alpha]; $x.__enum__ = openfl__$internal_renderer_opengl_utils_FillType; $x.toString = $estr; return $x; };
openfl__$internal_renderer_opengl_utils_FillType.Texture = function(bitmap,matrix,repeat,smooth) { var $x = ["Texture",2,bitmap,matrix,repeat,smooth]; $x.__enum__ = openfl__$internal_renderer_opengl_utils_FillType; $x.toString = $estr; return $x; };
openfl__$internal_renderer_opengl_utils_FillType.Gradient = ["Gradient",3];
openfl__$internal_renderer_opengl_utils_FillType.Gradient.toString = $estr;
openfl__$internal_renderer_opengl_utils_FillType.Gradient.__enum__ = openfl__$internal_renderer_opengl_utils_FillType;
var openfl__$internal_renderer_opengl_utils_FilterManager = function(gl,transparent) {
	this.transparent = transparent;
	this.filterStack = [];
	this.offsetX = 0;
	this.offsetY = 0;
	this.setContext(gl);
};
$hxClasses["openfl._internal.renderer.opengl.utils.FilterManager"] = openfl__$internal_renderer_opengl_utils_FilterManager;
openfl__$internal_renderer_opengl_utils_FilterManager.__name__ = true;
openfl__$internal_renderer_opengl_utils_FilterManager.prototype = {
	begin: function(renderSession,buffer) {
		this.renderSession = renderSession;
		this.defaultShader = renderSession.shaderManager.defaultShader;
		this.width = 0;
		this.height = 0;
		this.buffer = buffer;
	}
	,initShaderBuffers: function() {
		var gl = this.gl;
		this.vertexBuffer = gl.createBuffer();
		this.uvBuffer = gl.createBuffer();
		this.colorBuffer = gl.createBuffer();
		this.indexBuffer = gl.createBuffer();
		var array = [0.0,0.0,1.0,0.0,0.0,1.0,1.0,1.0];
		var this1;
		if(array != null) this1 = new Float32Array(array); else this1 = null;
		this.vertexArray = this1;
		gl.bindBuffer(gl.ARRAY_BUFFER,this.vertexBuffer);
		gl.bufferData(gl.ARRAY_BUFFER,this.vertexArray,gl.STATIC_DRAW);
		var array1 = [0.0,0.0,1.0,0.0,0.0,1.0,1.0,1.0];
		var this2;
		if(array1 != null) this2 = new Float32Array(array1); else this2 = null;
		this.uvArray = this2;
		gl.bindBuffer(gl.ARRAY_BUFFER,this.uvBuffer);
		gl.bufferData(gl.ARRAY_BUFFER,this.uvArray,gl.STATIC_DRAW);
		var array2 = [1.0,16777215,1.0,16777215,1.0,16777215,1.0,16777215];
		var this3;
		if(array2 != null) this3 = new Float32Array(array2); else this3 = null;
		this.colorArray = this3;
		gl.bindBuffer(gl.ARRAY_BUFFER,this.colorBuffer);
		gl.bufferData(gl.ARRAY_BUFFER,this.colorArray,gl.STATIC_DRAW);
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,this.indexBuffer);
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER,(function($this) {
			var $r;
			var array3 = [0,1,2,1,3,2];
			var this4;
			if(array3 != null) this4 = new Uint16Array(array3); else this4 = null;
			$r = this4;
			return $r;
		}(this)),gl.STATIC_DRAW);
	}
	,setContext: function(gl) {
		this.gl = gl;
		this.texturePool = [];
		this.initShaderBuffers();
	}
	,__class__: openfl__$internal_renderer_opengl_utils_FilterManager
};
var openfl__$internal_renderer_opengl_utils_GLMaskManager = function(renderSession) {
	openfl__$internal_renderer_AbstractMaskManager.call(this,renderSession);
	this.setContext(renderSession.gl);
	this.clips = [];
};
$hxClasses["openfl._internal.renderer.opengl.utils.GLMaskManager"] = openfl__$internal_renderer_opengl_utils_GLMaskManager;
openfl__$internal_renderer_opengl_utils_GLMaskManager.__name__ = true;
openfl__$internal_renderer_opengl_utils_GLMaskManager.__super__ = openfl__$internal_renderer_AbstractMaskManager;
openfl__$internal_renderer_opengl_utils_GLMaskManager.prototype = $extend(openfl__$internal_renderer_AbstractMaskManager.prototype,{
	pushRect: function(rect,transform) {
		if(rect == null) return;
		var m = new openfl_geom_Matrix(transform.a,transform.b,transform.c,transform.d,transform.tx,transform.ty);
		openfl__$internal_renderer_opengl_GLBitmap.flipMatrix(m,this.renderSession.renderer.viewport.height);
		var clip = rect.clone();
		clip.__transform(clip,m);
		if(this.currentClip != null) clip = this.currentClip.intersection(clip);
		var restartBatch = this.currentClip == null || clip.isEmpty() || this.currentClip.containsRect(clip);
		this.clips.push(clip);
		this.currentClip = clip;
		if(restartBatch) {
			this.renderSession.spriteBatch.stop();
			this.renderSession.spriteBatch.start(this.currentClip);
		}
	}
	,pushMask: function(mask) {
		this.renderSession.spriteBatch.stop();
		this.renderSession.stencilManager.pushMask(mask,this.renderSession);
		this.renderSession.spriteBatch.start(this.currentClip);
	}
	,popMask: function() {
		this.renderSession.spriteBatch.stop();
		this.renderSession.stencilManager.popMask(null,this.renderSession);
		this.renderSession.spriteBatch.start(this.currentClip);
	}
	,popRect: function() {
		this.renderSession.spriteBatch.stop();
		this.clips.pop();
		this.currentClip = this.clips[this.clips.length - 1];
		this.renderSession.spriteBatch.start(this.currentClip);
	}
	,saveState: function() {
		this.savedClip = this.currentClip;
		this.currentClip = null;
	}
	,restoreState: function() {
		this.currentClip = this.savedClip;
		this.savedClip = null;
	}
	,setContext: function(gl) {
		if(this.renderSession != null) this.renderSession.gl = gl;
		this.gl = gl;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_GLMaskManager
});
var openfl__$internal_renderer_opengl_utils_VertexAttribute = function(components,type,normalized,name,defaultValue) {
	if(normalized == null) normalized = false;
	this.enabled = true;
	this.normalized = false;
	this.components = components;
	this.type = type;
	this.normalized = normalized;
	this.name = name;
	if(defaultValue == null) {
		var this1;
		if(components != null) this1 = new Float32Array(components); else this1 = null;
		this.defaultValue = this1;
	} else this.defaultValue = defaultValue;
};
$hxClasses["openfl._internal.renderer.opengl.utils.VertexAttribute"] = openfl__$internal_renderer_opengl_utils_VertexAttribute;
openfl__$internal_renderer_opengl_utils_VertexAttribute.__name__ = true;
openfl__$internal_renderer_opengl_utils_VertexAttribute.prototype = {
	copy: function() {
		return new openfl__$internal_renderer_opengl_utils_VertexAttribute(this.components,this.type,this.normalized,this.name,this.defaultValue);
	}
	,getElementsBytes: function() {
		var _g = this.type;
		switch(_g) {
		case 5120:case 5121:
			return 1;
		case 5122:case 5123:
			return 2;
		default:
			return 4;
		}
	}
	,__class__: openfl__$internal_renderer_opengl_utils_VertexAttribute
};
var openfl_geom_Rectangle = function(x,y,width,height) {
	if(height == null) height = 0;
	if(width == null) width = 0;
	if(y == null) y = 0;
	if(x == null) x = 0;
	this.x = x;
	this.y = y;
	this.width = width;
	this.height = height;
};
$hxClasses["openfl.geom.Rectangle"] = openfl_geom_Rectangle;
openfl_geom_Rectangle.__name__ = true;
openfl_geom_Rectangle.prototype = {
	clone: function() {
		return new openfl_geom_Rectangle(this.x,this.y,this.width,this.height);
	}
	,contains: function(x,y) {
		return x >= this.x && y >= this.y && x < this.get_right() && y < this.get_bottom();
	}
	,containsPoint: function(point) {
		return this.contains(point.x,point.y);
	}
	,containsRect: function(rect) {
		if(rect.width <= 0 || rect.height <= 0) return rect.x > this.x && rect.y > this.y && rect.get_right() < this.get_right() && rect.get_bottom() < this.get_bottom(); else return rect.x >= this.x && rect.y >= this.y && rect.get_right() <= this.get_right() && rect.get_bottom() <= this.get_bottom();
	}
	,copyFrom: function(sourceRect) {
		this.x = sourceRect.x;
		this.y = sourceRect.y;
		this.width = sourceRect.width;
		this.height = sourceRect.height;
	}
	,intersection: function(toIntersect) {
		var x0;
		if(this.x < toIntersect.x) x0 = toIntersect.x; else x0 = this.x;
		var x1;
		if(this.get_right() > toIntersect.get_right()) x1 = toIntersect.get_right(); else x1 = this.get_right();
		if(x1 <= x0) return new openfl_geom_Rectangle();
		var y0;
		if(this.y < toIntersect.y) y0 = toIntersect.y; else y0 = this.y;
		var y1;
		if(this.get_bottom() > toIntersect.get_bottom()) y1 = toIntersect.get_bottom(); else y1 = this.get_bottom();
		if(y1 <= y0) return new openfl_geom_Rectangle();
		return new openfl_geom_Rectangle(x0,y0,x1 - x0,y1 - y0);
	}
	,intersects: function(toIntersect) {
		var x0;
		if(this.x < toIntersect.x) x0 = toIntersect.x; else x0 = this.x;
		var x1;
		if(this.get_right() > toIntersect.get_right()) x1 = toIntersect.get_right(); else x1 = this.get_right();
		if(x1 <= x0) return false;
		var y0;
		if(this.y < toIntersect.y) y0 = toIntersect.y; else y0 = this.y;
		var y1;
		if(this.get_bottom() > toIntersect.get_bottom()) y1 = toIntersect.get_bottom(); else y1 = this.get_bottom();
		return y1 > y0;
	}
	,isEmpty: function() {
		return this.width <= 0 || this.height <= 0;
	}
	,setEmpty: function() {
		this.x = this.y = this.width = this.height = 0;
	}
	,setTo: function(xa,ya,widtha,heighta) {
		this.x = xa;
		this.y = ya;
		this.width = widtha;
		this.height = heighta;
	}
	,__expand: function(x,y,width,height) {
		if(this.width == 0 && this.height == 0) {
			this.x = x;
			this.y = y;
			this.width = width;
			this.height = height;
			return;
		}
		var cacheRight = this.get_right();
		var cacheBottom = this.get_bottom();
		if(this.x > x) {
			this.x = x;
			this.width = cacheRight - x;
		}
		if(this.y > y) {
			this.y = y;
			this.height = cacheBottom - y;
		}
		if(cacheRight < x + width) this.width = x + width - this.x;
		if(cacheBottom < y + height) this.height = y + height - this.y;
	}
	,__toLimeRectangle: function() {
		return new lime_math_Rectangle(this.x,this.y,this.width,this.height);
	}
	,__transform: function(rect,m) {
		var tx0 = m.a * this.x + m.c * this.y;
		var tx1 = tx0;
		var ty0 = m.b * this.x + m.d * this.y;
		var ty1 = ty0;
		var tx = m.a * (this.x + this.width) + m.c * this.y;
		var ty = m.b * (this.x + this.width) + m.d * this.y;
		if(tx < tx0) tx0 = tx;
		if(ty < ty0) ty0 = ty;
		if(tx > tx1) tx1 = tx;
		if(ty > ty1) ty1 = ty;
		tx = m.a * (this.x + this.width) + m.c * (this.y + this.height);
		ty = m.b * (this.x + this.width) + m.d * (this.y + this.height);
		if(tx < tx0) tx0 = tx;
		if(ty < ty0) ty0 = ty;
		if(tx > tx1) tx1 = tx;
		if(ty > ty1) ty1 = ty;
		tx = m.a * this.x + m.c * (this.y + this.height);
		ty = m.b * this.x + m.d * (this.y + this.height);
		if(tx < tx0) tx0 = tx;
		if(ty < ty0) ty0 = ty;
		if(tx > tx1) tx1 = tx;
		if(ty > ty1) ty1 = ty;
		rect.setTo(tx0 + m.tx,ty0 + m.ty,tx1 - tx0,ty1 - ty0);
	}
	,get_bottom: function() {
		return this.y + this.height;
	}
	,get_left: function() {
		return this.x;
	}
	,get_right: function() {
		return this.x + this.width;
	}
	,get_top: function() {
		return this.y;
	}
	,get_topLeft: function() {
		return new openfl_geom_Point(this.x,this.y);
	}
	,__class__: openfl_geom_Rectangle
	,__properties__: {get_topLeft:"get_topLeft",get_top:"get_top",get_right:"get_right",get_left:"get_left",get_bottom:"get_bottom"}
};
var openfl__$internal_renderer_opengl_utils_GraphicsRenderer = function() { };
$hxClasses["openfl._internal.renderer.opengl.utils.GraphicsRenderer"] = openfl__$internal_renderer_opengl_utils_GraphicsRenderer;
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.__name__ = true;
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.overrideMatrix = null;
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildCircle = function(path,glStack,localCoords) {
	if(localCoords == null) localCoords = false;
	var rectData = path.points;
	var x = rectData[0];
	var y = rectData[1];
	var rx = rectData[2];
	var ry;
	if(rectData.length == 3) ry = rx; else ry = rectData[3];
	if(path.type == openfl__$internal_renderer_opengl_utils_GraphicType.Ellipse) {
		rx /= 2;
		ry /= 2;
		x += rx;
		y += ry;
	}
	if(localCoords) {
		x -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.x;
		y -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.y;
	}
	var totalSegs = 40;
	var seg = Math.PI * 2 / totalSegs;
	var bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareBucket(path,glStack);
	var fill = bucket.getData(openfl__$internal_renderer_opengl_utils_BucketDataType.Fill);
	if(fill != null) {
		var verts = fill.verts;
		var indices = fill.indices;
		var vertPos = verts.length / 2 | 0;
		indices.push(vertPos);
		var _g1 = 0;
		var _g = totalSegs + 1;
		while(_g1 < _g) {
			var i = _g1++;
			verts.push(x);
			verts.push(y);
			verts.push(x + Math.sin(seg * i) * rx);
			verts.push(y + Math.cos(seg * i) * ry);
			indices.push(vertPos++);
			indices.push(vertPos++);
		}
		indices.push(vertPos - 1);
	}
	if(path.line.width > 0) {
		var tempPoints = path.points;
		path.points = [];
		openfl__$internal_renderer_GraphicsPaths.ellipse(path.points,x,y,rx,ry,totalSegs);
		openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildLine(path,bucket);
		path.points = tempPoints;
	}
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildComplexPoly = function(path,glStack,localCoords) {
	if(localCoords == null) localCoords = false;
	var bucket = null;
	if(path.points.length >= 6) {
		var points = path.points.slice();
		if(localCoords) {
			var _g1 = 0;
			var _g = points.length / 2 | 0;
			while(_g1 < _g) {
				var i = _g1++;
				points[i * 2] -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.x;
				points[i * 2 + 1] -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.y;
			}
		}
		bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareBucket(path,glStack);
		var fill = bucket.getData(openfl__$internal_renderer_opengl_utils_BucketDataType.Fill);
		fill.drawMode = glStack.gl.TRIANGLE_FAN;
		fill.verts = points;
		var indices = fill.indices;
		var length = points.length / 2 | 0;
		var _g2 = 0;
		while(_g2 < length) {
			var i1 = _g2++;
			indices.push(i1);
		}
	}
	if(path.line.width > 0) {
		if(bucket == null) bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareBucket(path,glStack);
		openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildLine(path,bucket,localCoords);
	}
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildLine = function(path,bucket,localCoords) {
	if(localCoords == null) localCoords = false;
	var points = path.points;
	if(points.length == 0) return;
	var line = bucket.getData(openfl__$internal_renderer_opengl_utils_BucketDataType.Line);
	if(localCoords) {
		var _g1 = 0;
		var _g = points.length / 2 | 0;
		while(_g1 < _g) {
			var i = _g1++;
			points[i * 2] -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.x;
			points[i * 2 + 1] -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.y;
		}
	}
	var firstPoint = new openfl_geom_Point(points[0],points[1]);
	var lastPoint = new openfl_geom_Point(points[points.length - 2 | 0],points[points.length - 1 | 0]);
	if(firstPoint.x == lastPoint.x && firstPoint.y == lastPoint.y) {
		points = points.slice();
		points.pop();
		points.pop();
		lastPoint = new openfl_geom_Point(points[points.length - 2 | 0],points[points.length - 1 | 0]);
		var midPointX = lastPoint.x + (firstPoint.x - lastPoint.x) * 0.5;
		var midPointY = lastPoint.y + (firstPoint.y - lastPoint.y) * 0.5;
		points.unshift(midPointY);
		points.unshift(midPointX);
		points.push(midPointX);
		points.push(midPointY);
	}
	var verts = line.verts;
	var indices = line.indices;
	var length = points.length / 2 | 0;
	var indexCount = points.length;
	var indexStart = verts.length / 6 | 0;
	var width = path.line.width / 2;
	var color = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.hex2rgb(path.line.color);
	var alpha = path.line.alpha;
	var r = color[0];
	var g = color[1];
	var b = color[2];
	var px;
	var py;
	var p1x;
	var p1y;
	var p2x;
	var p2y;
	var p3x;
	var p3y;
	var perpx;
	var perpy;
	var perp2x;
	var perp2y;
	var perp3x;
	var perp3y;
	var a1;
	var b1;
	var c1;
	var a2;
	var b2;
	var c2;
	var denom;
	var pdist;
	var dist;
	p1x = points[0];
	p1y = points[1];
	p2x = points[2];
	p2y = points[3];
	perpx = -(p1y - p2y);
	perpy = p1x - p2x;
	dist = Math.sqrt(Math.abs(perpx * perpx + perpy * perpy));
	perpx = perpx / dist;
	perpy = perpy / dist;
	perpx = perpx * width;
	perpy = perpy * width;
	verts.push(p1x - perpx);
	verts.push(p1y - perpy);
	verts.push(r);
	verts.push(g);
	verts.push(b);
	verts.push(alpha);
	verts.push(p1x + perpx);
	verts.push(p1y + perpy);
	verts.push(r);
	verts.push(g);
	verts.push(b);
	verts.push(alpha);
	var _g11 = 1;
	var _g2 = length - 1;
	while(_g11 < _g2) {
		var i1 = _g11++;
		p1x = points[(i1 - 1) * 2];
		p1y = points[(i1 - 1) * 2 + 1];
		p2x = points[i1 * 2];
		p2y = points[i1 * 2 + 1];
		p3x = points[(i1 + 1) * 2];
		p3y = points[(i1 + 1) * 2 + 1];
		perpx = -(p1y - p2y);
		perpy = p1x - p2x;
		dist = Math.sqrt(Math.abs(perpx * perpx + perpy * perpy));
		perpx = perpx / dist;
		perpy = perpy / dist;
		perpx = perpx * width;
		perpy = perpy * width;
		perp2x = -(p2y - p3y);
		perp2y = p2x - p3x;
		dist = Math.sqrt(Math.abs(perp2x * perp2x + perp2y * perp2y));
		perp2x = perp2x / dist;
		perp2y = perp2y / dist;
		perp2x = perp2x * width;
		perp2y = perp2y * width;
		a1 = -perpy + p1y - (-perpy + p2y);
		b1 = -perpx + p2x - (-perpx + p1x);
		c1 = (-perpx + p1x) * (-perpy + p2y) - (-perpx + p2x) * (-perpy + p1y);
		a2 = -perp2y + p3y - (-perp2y + p2y);
		b2 = -perp2x + p2x - (-perp2x + p3x);
		c2 = (-perp2x + p3x) * (-perp2y + p2y) - (-perp2x + p2x) * (-perp2y + p3y);
		denom = a1 * b2 - a2 * b1;
		if(Math.abs(denom) < 0.1) {
			denom += 10.1;
			verts.push(p2x - perpx);
			verts.push(p2y - perpy);
			verts.push(r);
			verts.push(g);
			verts.push(b);
			verts.push(alpha);
			verts.push(p2x + perpx);
			verts.push(p2y + perpy);
			verts.push(r);
			verts.push(g);
			verts.push(b);
			verts.push(alpha);
			continue;
		}
		px = (b1 * c2 - b2 * c1) / denom;
		py = (a2 * c1 - a1 * c2) / denom;
		pdist = (px - p2x) * (px - p2x) + (py - p2y) + (py - p2y);
		if(pdist > 19600) {
			perp3x = perpx - perp2x;
			perp3y = perpy - perp2y;
			dist = Math.sqrt(Math.abs(perp3x * perp3x + perp3y * perp3y));
			perp3x = perp3x / dist;
			perp3y = perp3y / dist;
			perp3x = perp3x * width;
			perp3y = perp3y * width;
			verts.push(p2x - perp3x);
			verts.push(p2y - perp3y);
			verts.push(r);
			verts.push(g);
			verts.push(b);
			verts.push(alpha);
			verts.push(p2x + perp3x);
			verts.push(p2y + perp3y);
			verts.push(r);
			verts.push(g);
			verts.push(b);
			verts.push(alpha);
			verts.push(p2x - perp3x);
			verts.push(p2y - perp3y);
			verts.push(r);
			verts.push(g);
			verts.push(b);
			verts.push(alpha);
			indexCount++;
		} else {
			verts.push(px);
			verts.push(py);
			verts.push(r);
			verts.push(g);
			verts.push(b);
			verts.push(alpha);
			verts.push(p2x - (px - p2x));
			verts.push(p2y - (py - p2y));
			verts.push(r);
			verts.push(g);
			verts.push(b);
			verts.push(alpha);
		}
	}
	p1x = points[(length - 2) * 2];
	p1y = points[(length - 2) * 2 + 1];
	p2x = points[(length - 1) * 2];
	p2y = points[(length - 1) * 2 + 1];
	perpx = -(p1y - p2y);
	perpy = p1x - p2x;
	dist = Math.sqrt(Math.abs(perpx * perpx + perpy * perpy));
	if(!isFinite(dist)) console.log(perpx * perpx + perpy * perpy);
	perpx = perpx / dist;
	perpy = perpy / dist;
	perpx = perpx * width;
	perpy = perpy * width;
	verts.push(p2x - perpx);
	verts.push(p2y - perpy);
	verts.push(r);
	verts.push(g);
	verts.push(b);
	verts.push(alpha);
	verts.push(p2x + perpx);
	verts.push(p2y + perpy);
	verts.push(r);
	verts.push(g);
	verts.push(b);
	verts.push(alpha);
	indices.push(indexStart);
	var _g3 = 0;
	while(_g3 < indexCount) {
		var i2 = _g3++;
		indices.push(indexStart++);
	}
	indices.push(indexStart - 1);
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildRectangle = function(path,glStack,localCoords) {
	if(localCoords == null) localCoords = false;
	var rectData = path.points;
	var x = rectData[0];
	var y = rectData[1];
	var width = rectData[2];
	var height = rectData[3];
	if(localCoords) {
		x -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.x;
		y -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.y;
	}
	var bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareBucket(path,glStack);
	var fill = bucket.getData(openfl__$internal_renderer_opengl_utils_BucketDataType.Fill);
	if(fill != null) {
		var verts = fill.verts;
		var indices = fill.indices;
		var vertPos = verts.length / 2 | 0;
		verts.push(x);
		verts.push(y);
		verts.push(x + width);
		verts.push(y);
		verts.push(x);
		verts.push(y + height);
		verts.push(x + width);
		verts.push(y + height);
		indices.push(vertPos);
		indices.push(vertPos);
		indices.push(vertPos + 1);
		indices.push(vertPos + 2);
		indices.push(vertPos + 3);
		indices.push(vertPos + 3);
	}
	if(path.line.width > 0) {
		var tempPoints = path.points;
		path.points = [x,y,x + width,y,x + width,y + height,x,y + height,x,y];
		openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildLine(path,bucket);
		path.points = tempPoints;
	}
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildRoundedRectangle = function(path,glStack,localCoords) {
	if(localCoords == null) localCoords = false;
	var points = path.points.slice();
	var x = points[0];
	var y = points[1];
	var width = points[2];
	var height = points[3];
	var rx = points[4];
	var ry = points[5];
	if(localCoords) {
		x -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.x;
		y -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.y;
	}
	var recPoints = [];
	openfl__$internal_renderer_GraphicsPaths.roundRectangle(recPoints,x,y,width,height,rx,ry);
	var bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareBucket(path,glStack);
	var fill = bucket.getData(openfl__$internal_renderer_opengl_utils_BucketDataType.Fill);
	if(fill != null) {
		var verts = fill.verts;
		var indices = fill.indices;
		var vecPos = verts.length / 2;
		var triangles = [];
		openfl__$internal_renderer_PolyK.triangulate(triangles,recPoints);
		var i = 0;
		while(i < triangles.length) {
			indices.push(triangles[i] + vecPos | 0);
			indices.push(triangles[i] + vecPos | 0);
			indices.push(triangles[i + 1] + vecPos | 0);
			indices.push(triangles[i + 2] + vecPos | 0);
			indices.push(triangles[i + 2] + vecPos | 0);
			i += 3;
		}
		i = 0;
		while(i < recPoints.length) {
			verts.push(recPoints[i]);
			verts.push(recPoints[++i]);
			i++;
		}
	}
	if(path.line.width > 0) {
		var tempPoints = path.points;
		path.points = recPoints;
		openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildLine(path,bucket);
		path.points = tempPoints;
	}
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildDrawTriangles = function(path,object,glStack,localCoords) {
	if(localCoords == null) localCoords = false;
	var args = path.type.slice(2);
	var vertices = args[0];
	var indices = args[1];
	var uvtData = args[2];
	var culling = args[3];
	var colors = args[4];
	var blendMode = args[5];
	var a;
	var b;
	var c;
	var d;
	var tx;
	var ty;
	if(localCoords) {
		a = 1.0;
		b = 0.0;
		c = 0.0;
		d = 1.0;
		tx = 0.0;
		ty = 0.0;
	} else {
		a = object.__worldTransform.a;
		b = object.__worldTransform.b;
		c = object.__worldTransform.c;
		d = object.__worldTransform.d;
		tx = object.__worldTransform.tx;
		ty = object.__worldTransform.ty;
	}
	var hasColors = colors != null && colors.length > 0;
	var bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareBucket(path,glStack);
	var fill = bucket.getData(openfl__$internal_renderer_opengl_utils_BucketDataType.Fill);
	var colorAttrib = fill.vertexArray.attributes[2];
	colorAttrib.enabled = hasColors;
	var array = [1,1,1,1];
	var this1;
	if(array != null) this1 = new Float32Array(array); else this1 = null;
	colorAttrib.defaultValue = this1;
	fill.rawVerts = true;
	fill.glLength = indices.length;
	fill.stride = Std["int"](fill.vertexArray.get_stride() / 4);
	var vertsLength = fill.glLength * fill.stride;
	var verts;
	if(fill.glVerts == null || fill.glVerts.length < vertsLength) {
		var this2;
		if(vertsLength != null) this2 = new Float32Array(vertsLength); else this2 = null;
		verts = this2;
		fill.glVerts = verts;
	} else verts = fill.glVerts;
	var glColors;
	var buffer = verts.buffer;
	var this3;
	if(buffer != null) this3 = new Uint32Array(buffer,0); else this3 = null;
	glColors = this3;
	var v0 = 0;
	var v1 = 0;
	var v2 = 0;
	var i0 = 0;
	var i1 = 0;
	var i2 = 0;
	var x0 = 0.0;
	var y0 = 0.0;
	var x1 = 0.0;
	var y1 = 0.0;
	var x2 = 0.0;
	var y2 = 0.0;
	var idx = 0;
	var _g1 = 0;
	var _g = indices.length / 3 | 0;
	while(_g1 < _g) {
		var i = _g1++;
		i0 = indices.data[i * 3];
		i1 = indices.data[i * 3 + 1];
		i2 = indices.data[i * 3 + 2];
		v0 = i0 * 2;
		v1 = i1 * 2;
		v2 = i2 * 2;
		x0 = vertices.data[v0];
		y0 = vertices.data[v0 + 1];
		x1 = vertices.data[v1];
		y1 = vertices.data[v1 + 1];
		x2 = vertices.data[v2];
		y2 = vertices.data[v2 + 1];
		if(localCoords) {
			x0 -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.x;
			y0 -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.y;
			x1 -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.x;
			y1 -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.y;
			x2 -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.x;
			y2 -= openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.y;
		}
		switch(culling[1]) {
		case 2:
			if(!((x1 - x0) * (y2 - y0) - (y1 - y0) * (x2 - x0) < 0)) continue;
			break;
		case 0:
			if((x1 - x0) * (y2 - y0) - (y1 - y0) * (x2 - x0) < 0) continue;
			break;
		default:
		}
		var idx1 = idx++;
		verts[idx1] = a * x0 + c * y0 + tx;
		var idx2 = idx++;
		verts[idx2] = b * x0 + d * y0 + ty;
		var idx3 = idx++;
		verts[idx3] = uvtData.data[v0];
		var idx4 = idx++;
		verts[idx4] = uvtData.data[v0 + 1];
		if(hasColors) {
			var idx5 = idx++;
			glColors[idx5] = colors.data[i0];
		}
		var idx6 = idx++;
		verts[idx6] = a * x1 + c * y1 + tx;
		var idx7 = idx++;
		verts[idx7] = b * x1 + d * y1 + ty;
		var idx8 = idx++;
		verts[idx8] = uvtData.data[v1];
		var idx9 = idx++;
		verts[idx9] = uvtData.data[v1 + 1];
		if(hasColors) {
			var idx10 = idx++;
			glColors[idx10] = colors.data[i1];
		}
		var idx11 = idx++;
		verts[idx11] = a * x2 + c * y2 + tx;
		var idx12 = idx++;
		verts[idx12] = b * x2 + d * y2 + ty;
		var idx13 = idx++;
		verts[idx13] = uvtData.data[v2];
		var idx14 = idx++;
		verts[idx14] = uvtData.data[v2 + 1];
		if(hasColors) {
			var idx15 = idx++;
			glColors[idx15] = colors.data[i2];
		}
	}
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.render = function(object,renderSession) {
	var graphics = object.__graphics;
	var bounds = graphics.__bounds;
	var spritebatch = renderSession.spriteBatch;
	var dirty = graphics.__dirty;
	if(!graphics.__visible || graphics.__commands.get_length() == 0 || bounds == null || bounds.width == 0 || bounds.height == 0) {
		graphics.__glStack.splice(0,graphics.__glStack.length);
		return;
	}
	if(dirty) openfl__$internal_renderer_opengl_utils_GraphicsRenderer.updateGraphics(object,object.__graphics,renderSession.gl,object.get_cacheAsBitmap());
	openfl__$internal_renderer_opengl_utils_GraphicsRenderer.renderGraphics(object,renderSession,false);
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.renderGraphics = function(object,renderSession,localCoords) {
	if(localCoords == null) localCoords = false;
	var graphics = object.__graphics;
	var gl = renderSession.gl;
	var glStack = graphics.__glStack[openfl__$internal_renderer_opengl_GLRenderer.glContextId];
	if(glStack == null) return;
	var bucket;
	var translationMatrix;
	if(localCoords) translationMatrix = openfl_geom_Matrix.__identity; else translationMatrix = object.__worldTransform;
	var clipRect = renderSession.spriteBatch.clipRect;
	var batchDrawing = renderSession.spriteBatch.drawing;
	batchDrawing = renderSession.spriteBatch.drawing;
	var _g1 = 0;
	var _g = glStack.buckets.length;
	while(_g1 < _g) {
		var i = _g1++;
		batchDrawing = renderSession.spriteBatch.drawing;
		if(batchDrawing && !localCoords) renderSession.spriteBatch.finish();
		renderSession.blendModeManager.setBlendMode(object.__blendMode);
		if(clipRect != null) {
			gl.enable(gl.SCISSOR_TEST);
			gl.scissor(Math.floor(clipRect.x),Math.floor(clipRect.y),Math.floor(clipRect.width),Math.floor(clipRect.height));
		}
		bucket = glStack.buckets[i];
		var _g2 = bucket.mode;
		switch(_g2[1]) {
		case 1:case 2:
			renderSession.stencilManager.pushBucket(bucket,renderSession,translationMatrix.toArray(true));
			var shader = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareShader(bucket,renderSession,object,translationMatrix.toArray(true));
			openfl__$internal_renderer_opengl_utils_GraphicsRenderer.renderFill(bucket,shader,renderSession);
			renderSession.stencilManager.popBucket(object,bucket,renderSession);
			break;
		case 5:
			var shader1 = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareShader(bucket,renderSession,object,null);
			openfl__$internal_renderer_opengl_utils_GraphicsRenderer.renderDrawTriangles(bucket,shader1,renderSession);
			break;
		case 6:
			if(!batchDrawing) renderSession.spriteBatch.begin(renderSession,clipRect);
			var args = bucket.graphicType.slice(2);
			renderSession.spriteBatch.renderTiles(object,args[0],args[1],args[2],args[3],args[4],args[5]);
			renderSession.spriteBatch.finish();
			break;
		default:
		}
		var ct = object.__worldColorTransform;
		var _g21 = 0;
		var _g3 = bucket.lines;
		while(_g21 < _g3.length) {
			var line = _g3[_g21];
			++_g21;
			if(line != null && line.verts.length > 0) {
				var shader2 = renderSession.shaderManager.primitiveShader;
				renderSession.shaderManager.setShader(shader2);
				gl.uniformMatrix3fv(shader2.getUniformLocation("openfl_uTranslationMatrix"),false,translationMatrix.toArray(true));
				gl.uniformMatrix3fv(shader2.getUniformLocation("openfl_uProjectionMatrix"),false,renderSession.projectionMatrix.toArray(true));
				gl.uniform1f(shader2.getUniformLocation("openfl_uAlpha"),1);
				gl.uniform4f(shader2.getUniformLocation("openfl_uColorMultiplier"),ct.redMultiplier,ct.greenMultiplier,ct.blueMultiplier,ct.alphaMultiplier);
				gl.uniform4f(shader2.getUniformLocation("openfl_uColorOffset"),ct.redOffset / 255,ct.greenOffset / 255,ct.blueOffset / 255,ct.alphaOffset / 255);
				line.vertexArray.bind();
				shader2.bindVertexArray(line.vertexArray);
				gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,line.indexBuffer);
				gl.drawElements(gl.TRIANGLE_STRIP,line.indices.length,gl.UNSIGNED_SHORT,0);
			}
		}
		if(clipRect != null) gl.disable(gl.SCISSOR_TEST);
		batchDrawing = renderSession.spriteBatch.drawing;
		if(!batchDrawing && !localCoords) renderSession.spriteBatch.begin(renderSession,clipRect);
	}
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.updateGraphics = function(object,graphics,gl,localCoords) {
	if(localCoords == null) localCoords = false;
	openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectPosition.setTo(object.get_x(),object.get_y());
	if(graphics.__bounds == null) openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds = new openfl_geom_Rectangle(); else openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.copyFrom(graphics.__bounds);
	var glStack = null;
	if(graphics.__dirty) glStack = openfl__$internal_renderer_opengl_utils_DrawPath.getStack(graphics,gl);
	graphics.set___dirty(false);
	var _g = 0;
	var _g1 = glStack.buckets;
	while(_g < _g1.length) {
		var data = _g1[_g];
		++_g;
		data.reset();
		openfl__$internal_renderer_opengl_utils_GraphicsRenderer.bucketPool.push(data);
	}
	glStack.reset();
	var _g11 = glStack.lastIndex;
	var _g2 = graphics.__drawPaths.length;
	while(_g11 < _g2) {
		var i = _g11++;
		var path = graphics.__drawPaths[i];
		{
			var _g21 = path.type;
			switch(_g21[1]) {
			case 0:
				openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildComplexPoly(path,glStack,localCoords);
				break;
			case 1:
				var rounded = _g21[2];
				if(rounded) openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildRoundedRectangle(path,glStack,localCoords); else openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildRectangle(path,glStack,localCoords);
				break;
			case 2:case 3:
				openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildCircle(path,glStack,localCoords);
				break;
			case 4:
				openfl__$internal_renderer_opengl_utils_GraphicsRenderer.buildDrawTriangles(path,object,glStack,localCoords);
				break;
			case 5:
				openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareBucket(path,glStack);
				break;
			case 6:
				var m = _g21[2];
				openfl__$internal_renderer_opengl_utils_GraphicsRenderer.overrideMatrix = m;
				break;
			}
		}
		glStack.lastIndex++;
	}
	var _g3 = 0;
	var _g12 = glStack.buckets;
	while(_g3 < _g12.length) {
		var bucket = _g12[_g3];
		++_g3;
		if(bucket.uploadTileBuffer) bucket.uploadTile(Math.ceil(openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.get_left()),Math.ceil(openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.get_top()),Math.floor(openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.get_right()),Math.floor(openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds.get_bottom()));
		bucket.optimize();
	}
	glStack.upload();
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareBucket = function(path,glStack) {
	var bucket = null;
	{
		var _g = path.fill;
		switch(_g[1]) {
		case 1:
			var a = _g[3];
			var c = _g[2];
			bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.switchBucket(path.fillIndex,glStack,openfl__$internal_renderer_opengl_utils_BucketMode.Fill);
			if(c == null) bucket.color = [1,1,1]; else bucket.color = [(c >> 16 & 255) / 255,(c >> 8 & 255) / 255,(c & 255) / 255];
			bucket.color[3] = a;
			bucket.uploadTileBuffer = true;
			break;
		case 2:
			var s = _g[5];
			var r = _g[4];
			var m = _g[3];
			var b = _g[2];
			bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.switchBucket(path.fillIndex,glStack,openfl__$internal_renderer_opengl_utils_BucketMode.PatternFill);
			bucket.bitmap = b;
			bucket.textureRepeat = r;
			bucket.textureSmooth = s;
			bucket.texture = b.getTexture(glStack.gl);
			bucket.uploadTileBuffer = true;
			var pMatrix;
			if(m == null) pMatrix = new openfl_geom_Matrix(); else pMatrix = new openfl_geom_Matrix(m.a,m.b,m.c,m.d,m.tx,m.ty);
			pMatrix.invert();
			pMatrix.scale(1 / b.width,1 / b.height);
			var tx = pMatrix.tx;
			var ty = pMatrix.ty;
			pMatrix.tx = 0;
			pMatrix.ty = 0;
			bucket.textureTL.x = tx;
			bucket.textureTL.y = ty;
			bucket.textureBR.x = tx + 1;
			bucket.textureBR.y = ty + 1;
			bucket.textureMatrix = pMatrix;
			break;
		default:
			bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.switchBucket(path.fillIndex,glStack,openfl__$internal_renderer_opengl_utils_BucketMode.Line);
			bucket.uploadTileBuffer = false;
		}
	}
	{
		var _g1 = path.type;
		switch(_g1[1]) {
		case 4:
			bucket.mode = openfl__$internal_renderer_opengl_utils_BucketMode.DrawTriangles;
			bucket.uploadTileBuffer = false;
			break;
		case 5:
			bucket.mode = openfl__$internal_renderer_opengl_utils_BucketMode.DrawTiles;
			bucket.uploadTileBuffer = false;
			break;
		default:
		}
	}
	bucket.graphicType = path.type;
	bucket.overrideMatrix = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.overrideMatrix;
	return bucket;
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.getBucket = function(glStack,mode) {
	var b = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.bucketPool.pop();
	if(b == null) b = new openfl__$internal_renderer_opengl_utils_GLBucket(glStack.gl);
	b.mode = mode;
	glStack.buckets.push(b);
	return b;
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.switchBucket = function(fillIndex,glStack,mode) {
	var bucket = null;
	var _g = 0;
	var _g1 = glStack.buckets;
	while(_g < _g1.length) {
		var b = _g1[_g];
		++_g;
		if(b.fillIndex == fillIndex) {
			bucket = b;
			break;
		}
	}
	if(bucket == null) bucket = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.getBucket(glStack,mode);
	bucket.dirty = true;
	bucket.fillIndex = fillIndex;
	return bucket;
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.prepareShader = function(bucket,renderSession,object,translationMatrix) {
	var gl = renderSession.gl;
	var shader = null;
	var _g = bucket.mode;
	switch(_g[1]) {
	case 1:
		shader = renderSession.shaderManager.fillShader;
		break;
	case 2:
		shader = renderSession.shaderManager.patternFillShader;
		break;
	case 5:
		shader = renderSession.shaderManager.drawTrianglesShader;
		break;
	default:
		shader = null;
	}
	if(shader == null) return null;
	var newShader = renderSession.shaderManager.setShader(shader);
	gl.uniform1f(shader.getUniformLocation("openfl_uAlpha"),object.__worldAlpha);
	gl.uniformMatrix3fv(shader.getUniformLocation("openfl_uProjectionMatrix"),false,renderSession.projectionMatrix.toArray(true));
	var ct = object.__worldColorTransform;
	gl.uniform4f(shader.getUniformLocation("openfl_uColorMultiplier"),ct.redMultiplier,ct.greenMultiplier,ct.blueMultiplier,ct.alphaMultiplier);
	gl.uniform4f(shader.getUniformLocation("openfl_uColorOffset"),ct.redOffset / 255,ct.greenOffset / 255,ct.blueOffset / 255,ct.alphaOffset / 255);
	var _g1 = bucket.mode;
	switch(_g1[1]) {
	case 1:
		gl.uniformMatrix3fv(shader.getUniformLocation("openfl_uTranslationMatrix"),false,translationMatrix);
		gl.uniform4fv(shader.getUniformLocation("openfl_uColor"),(function($this) {
			var $r;
			var array = bucket.color;
			var this1;
			if(array != null) this1 = new Float32Array(array); else this1 = null;
			$r = this1;
			return $r;
		}(this)));
		break;
	case 2:
		gl.uniformMatrix3fv(shader.getUniformLocation("openfl_uTranslationMatrix"),false,translationMatrix);
		gl.uniform2f(shader.getUniformLocation("openfl_uPatternTL"),bucket.textureTL.x,bucket.textureTL.y);
		gl.uniform2f(shader.getUniformLocation("openfl_uPatternBR"),bucket.textureBR.x,bucket.textureBR.y);
		gl.uniformMatrix3fv(shader.getUniformLocation("openfl_uPatternMatrix"),false,bucket.textureMatrix.toArray(true));
		break;
	case 5:
		if(bucket.texture != null) gl.uniform1i(shader.getUniformLocation("openfl_uUseTexture"),1); else {
			gl.uniform1i(shader.getUniformLocation("openfl_uUseTexture"),0);
			gl.uniform4fv(shader.getUniformLocation("openfl_uColor"),(function($this) {
				var $r;
				var array1 = bucket.color;
				var this2;
				if(array1 != null) this2 = new Float32Array(array1); else this2 = null;
				$r = this2;
				return $r;
			}(this)));
		}
		break;
	default:
	}
	return shader;
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.renderFill = function(bucket,shader,renderSession) {
	var gl = renderSession.gl;
	if(bucket.mode == openfl__$internal_renderer_opengl_utils_BucketMode.PatternFill && bucket.texture != null) openfl__$internal_renderer_opengl_utils_GraphicsRenderer.bindTexture(gl,bucket);
	gl.bindBuffer(gl.ARRAY_BUFFER,bucket.tileBuffer);
	gl.vertexAttribPointer(shader.getAttribLocation("openfl_aPosition"),4,gl.SHORT,false,0,0);
	gl.drawArrays(gl.TRIANGLE_STRIP,0,4);
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.renderDrawTriangles = function(bucket,shader,renderSession) {
	var gl = renderSession.gl;
	var _g = 0;
	var _g1 = bucket.fills;
	while(_g < _g1.length) {
		var fill = _g1[_g];
		++_g;
		if(fill.available) continue;
		openfl__$internal_renderer_opengl_utils_GraphicsRenderer.bindTexture(gl,bucket);
		fill.vertexArray.bind();
		shader.bindVertexArray(fill.vertexArray);
		gl.drawArrays(gl.TRIANGLES,fill.glStart,fill.glLength);
	}
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.bindTexture = function(gl,bucket) {
	gl.bindTexture(gl.TEXTURE_2D,bucket.texture);
	if(bucket.textureRepeat && bucket.bitmap.image.get_powerOfTwo()) {
		gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_S,gl.REPEAT);
		gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_T,gl.REPEAT);
	} else {
		gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_S,gl.CLAMP_TO_EDGE);
		gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_T,gl.CLAMP_TO_EDGE);
	}
	if(bucket.textureSmooth) {
		gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MAG_FILTER,gl.LINEAR);
		gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MIN_FILTER,gl.LINEAR);
	} else {
		gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MAG_FILTER,gl.NEAREST);
		gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MIN_FILTER,gl.NEAREST);
	}
};
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.hex2rgb = function(hex) {
	if(hex == null) return [1,1,1]; else return [(hex >> 16 & 255) / 255,(hex >> 8 & 255) / 255,(hex & 255) / 255];
};
var openfl__$internal_renderer_opengl_utils_GLStack = function(gl) {
	this.lastIndex = 0;
	this.gl = gl;
	this.buckets = [];
	this.lastIndex = 0;
};
$hxClasses["openfl._internal.renderer.opengl.utils.GLStack"] = openfl__$internal_renderer_opengl_utils_GLStack;
openfl__$internal_renderer_opengl_utils_GLStack.__name__ = true;
openfl__$internal_renderer_opengl_utils_GLStack.prototype = {
	reset: function() {
		this.buckets = [];
		this.lastIndex = 0;
	}
	,upload: function() {
		var _g = 0;
		var _g1 = this.buckets;
		while(_g < _g1.length) {
			var bucket = _g1[_g];
			++_g;
			if(bucket.dirty) bucket.upload();
		}
	}
	,__class__: openfl__$internal_renderer_opengl_utils_GLStack
};
var openfl__$internal_renderer_opengl_utils_GLBucket = function(gl) {
	this.uploadTileBuffer = true;
	this.textureSmooth = true;
	this.textureRepeat = false;
	this.lines = [];
	this.fills = [];
	this.fillIndex = -1;
	this.gl = gl;
	this.color = [0,0,0];
	this.lastIndex = 0;
	this.alpha = 1;
	this.dirty = true;
	this.mode = openfl__$internal_renderer_opengl_utils_BucketMode.Fill;
	this.textureMatrix = new openfl_geom_Matrix();
	this.textureTL = new openfl_geom_Point();
	this.textureBR = new openfl_geom_Point(1,1);
};
$hxClasses["openfl._internal.renderer.opengl.utils.GLBucket"] = openfl__$internal_renderer_opengl_utils_GLBucket;
openfl__$internal_renderer_opengl_utils_GLBucket.__name__ = true;
openfl__$internal_renderer_opengl_utils_GLBucket.prototype = {
	getData: function(type) {
		var data;
		switch(type[1]) {
		case 1:
			data = this.fills;
			break;
		default:
			data = this.lines;
		}
		var result = null;
		var remove = false;
		var _g = 0;
		while(_g < data.length) {
			var d = data[_g];
			++_g;
			if(d.available) {
				result = d;
				remove = true;
				break;
			}
		}
		if(result == null) result = new openfl__$internal_renderer_opengl_utils_GLBucketData(this.gl);
		result.available = false;
		result.parent = this;
		result.type = type;
		if(remove) HxOverrides.remove(data,result);
		data.push(result);
		switch(type[1]) {
		case 1:
			var _g1 = this.mode;
			switch(_g1[1]) {
			case 1:case 2:
				result.vertexArray.attributes = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.fillVertexAttributes;
				break;
			case 5:
				result.vertexArray.attributes = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.drawTrianglesVertexAttributes.slice();
				result.vertexArray.attributes[2] = result.vertexArray.attributes[2].copy();
				break;
			default:
			}
			break;
		case 0:
			result.vertexArray.attributes = openfl__$internal_renderer_opengl_utils_GraphicsRenderer.primitiveVertexAttributes;
			break;
		}
		return result;
	}
	,optimize: function() {
		var _g = this;
		var data = this.lines;
		if(data.length > 1) {
			var result = [];
			var tmp = null;
			var last = null;
			var idx = 0;
			var vi = 0;
			var ii = 0;
			var before = data.length;
			var _g1 = 0;
			while(_g1 < data.length) {
				var d = data[_g1];
				++_g1;
				if(d.available || d.rawVerts || d.rawIndices) {
					if(tmp != null) {
						result.push(tmp);
						tmp = null;
					}
					result.push(d);
					last = d;
					continue;
				}
				if(last == null || last.drawMode == d.drawMode) {
					if(tmp == null) tmp = d; else {
						vi = tmp.verts.length;
						ii = tmp.indices.length;
						var _g2 = 0;
						var _g11 = d.verts.length;
						while(_g2 < _g11) {
							var j = _g2++;
							tmp.verts[j + vi] = d.verts[j];
						}
						var _g21 = 0;
						var _g12 = d.indices.length;
						while(_g21 < _g12) {
							var j1 = _g21++;
							tmp.indices[j1 + ii] = d.indices[j1] + idx;
						}
					}
					idx = tmp.indices[tmp.indices.length - 1] + 1;
					last = d;
				} else {
					if(tmp != null) {
						result.push(tmp);
						tmp = null;
					}
					result.push(d);
					last = d;
					continue;
				}
			}
			if(result.length == 0 && tmp != null) result.push(tmp);
			if(result.length > 0) switch(openfl__$internal_renderer_opengl_utils_BucketDataType.Line[1]) {
			case 1:
				_g.fills = result;
				break;
			default:
				_g.lines = result;
			}
		}
	}
	,reset: function() {
		var _g = 0;
		var _g1 = this.fills;
		while(_g < _g1.length) {
			var fill = _g1[_g];
			++_g;
			fill.reset();
		}
		var _g2 = 0;
		var _g11 = this.lines;
		while(_g2 < _g11.length) {
			var line = _g11[_g2];
			++_g2;
			line.reset();
		}
		this.fillIndex = -1;
		this.uploadTileBuffer = true;
		this.graphicType = openfl__$internal_renderer_opengl_utils_GraphicType.Polygon;
	}
	,uploadTile: function(x,y,w,h) {
		if(this.tileBuffer == null) this.tileBuffer = this.gl.createBuffer();
		this.tile = [x,y,0,0,w,y,1,0,x,h,0,1,w,h,1,1];
		var array = this.tile;
		var this1;
		if(array != null) this1 = new Int16Array(array); else this1 = null;
		this.glTile = this1;
		this.gl.bindBuffer(this.gl.ARRAY_BUFFER,this.tileBuffer);
		this.gl.bufferData(this.gl.ARRAY_BUFFER,this.glTile,this.gl.STATIC_DRAW);
	}
	,upload: function() {
		if(this.mode != openfl__$internal_renderer_opengl_utils_BucketMode.Line) {
			var _g = 0;
			var _g1 = this.fills;
			while(_g < _g1.length) {
				var fill = _g1[_g];
				++_g;
				if(!fill.available) fill.upload();
			}
		}
		var _g2 = 0;
		var _g11 = this.lines;
		while(_g2 < _g11.length) {
			var line = _g11[_g2];
			++_g2;
			if(!line.available) line.upload();
		}
		this.dirty = false;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_GLBucket
};
var openfl__$internal_renderer_opengl_utils_GLBucketData = function(gl) {
	this.available = false;
	this.rawIndices = false;
	this.stride = 0;
	this.rawVerts = false;
	this.lastVertsSize = 0;
	this.glStart = 0;
	this.glLength = 0;
	this.gl = gl;
	this.drawMode = gl.TRIANGLE_STRIP;
	this.verts = [];
	this.indices = [];
	this.vertexArray = new openfl__$internal_renderer_opengl_utils_VertexArray([]);
};
$hxClasses["openfl._internal.renderer.opengl.utils.GLBucketData"] = openfl__$internal_renderer_opengl_utils_GLBucketData;
openfl__$internal_renderer_opengl_utils_GLBucketData.__name__ = true;
openfl__$internal_renderer_opengl_utils_GLBucketData.prototype = {
	reset: function() {
		this.available = true;
		this.verts = [];
		this.indices = [];
		this.glLength = 0;
		this.glStart = 0;
		this.stride = 0;
		this.rawVerts = false;
		this.rawIndices = false;
		this.drawMode = this.gl.TRIANGLE_STRIP;
	}
	,upload: function() {
		if(this.rawVerts && this.glVerts != null && this.glVerts.length > 0 || this.verts.length > 0) {
			if(!this.rawVerts) {
				var array = this.verts;
				var this1;
				if(array != null) this1 = new Float32Array(array); else this1 = null;
				this.glVerts = this1;
			}
			this.vertexArray.buffer = this.glVerts.buffer;
			if(this.glVerts.length <= this.lastVertsSize) {
				this.vertexArray.bind();
				var end = this.glLength * this.stride;
				if(this.glLength > 0 && this.lastVertsSize > end) {
					var view = this.glVerts.subarray(0,end);
					this.vertexArray.upload(view);
				} else this.vertexArray.upload(this.glVerts);
			} else {
				this.vertexArray.setContext(this.gl,this.glVerts);
				this.lastVertsSize = this.glVerts.length;
			}
		}
		if(this.glLength == 0 && (this.rawIndices && this.glIndices != null && this.glIndices.length > 0 || this.indices.length > 0)) {
			if(this.indexBuffer == null) this.indexBuffer = this.gl.createBuffer();
			if(!this.rawIndices) {
				var array1 = this.indices;
				var this2;
				if(array1 != null) this2 = new Uint16Array(array1); else this2 = null;
				this.glIndices = this2;
			}
			this.gl.bindBuffer(this.gl.ELEMENT_ARRAY_BUFFER,this.indexBuffer);
			this.gl.bufferData(this.gl.ELEMENT_ARRAY_BUFFER,this.glIndices,this.gl.STREAM_DRAW);
		}
	}
	,__class__: openfl__$internal_renderer_opengl_utils_GLBucketData
};
var openfl__$internal_renderer_opengl_utils_BucketMode = $hxClasses["openfl._internal.renderer.opengl.utils.BucketMode"] = { __ename__ : true, __constructs__ : ["None","Fill","PatternFill","Line","PatternLine","DrawTriangles","DrawTiles"] };
openfl__$internal_renderer_opengl_utils_BucketMode.None = ["None",0];
openfl__$internal_renderer_opengl_utils_BucketMode.None.toString = $estr;
openfl__$internal_renderer_opengl_utils_BucketMode.None.__enum__ = openfl__$internal_renderer_opengl_utils_BucketMode;
openfl__$internal_renderer_opengl_utils_BucketMode.Fill = ["Fill",1];
openfl__$internal_renderer_opengl_utils_BucketMode.Fill.toString = $estr;
openfl__$internal_renderer_opengl_utils_BucketMode.Fill.__enum__ = openfl__$internal_renderer_opengl_utils_BucketMode;
openfl__$internal_renderer_opengl_utils_BucketMode.PatternFill = ["PatternFill",2];
openfl__$internal_renderer_opengl_utils_BucketMode.PatternFill.toString = $estr;
openfl__$internal_renderer_opengl_utils_BucketMode.PatternFill.__enum__ = openfl__$internal_renderer_opengl_utils_BucketMode;
openfl__$internal_renderer_opengl_utils_BucketMode.Line = ["Line",3];
openfl__$internal_renderer_opengl_utils_BucketMode.Line.toString = $estr;
openfl__$internal_renderer_opengl_utils_BucketMode.Line.__enum__ = openfl__$internal_renderer_opengl_utils_BucketMode;
openfl__$internal_renderer_opengl_utils_BucketMode.PatternLine = ["PatternLine",4];
openfl__$internal_renderer_opengl_utils_BucketMode.PatternLine.toString = $estr;
openfl__$internal_renderer_opengl_utils_BucketMode.PatternLine.__enum__ = openfl__$internal_renderer_opengl_utils_BucketMode;
openfl__$internal_renderer_opengl_utils_BucketMode.DrawTriangles = ["DrawTriangles",5];
openfl__$internal_renderer_opengl_utils_BucketMode.DrawTriangles.toString = $estr;
openfl__$internal_renderer_opengl_utils_BucketMode.DrawTriangles.__enum__ = openfl__$internal_renderer_opengl_utils_BucketMode;
openfl__$internal_renderer_opengl_utils_BucketMode.DrawTiles = ["DrawTiles",6];
openfl__$internal_renderer_opengl_utils_BucketMode.DrawTiles.toString = $estr;
openfl__$internal_renderer_opengl_utils_BucketMode.DrawTiles.__enum__ = openfl__$internal_renderer_opengl_utils_BucketMode;
var openfl__$internal_renderer_opengl_utils_BucketDataType = $hxClasses["openfl._internal.renderer.opengl.utils.BucketDataType"] = { __ename__ : true, __constructs__ : ["Line","Fill"] };
openfl__$internal_renderer_opengl_utils_BucketDataType.Line = ["Line",0];
openfl__$internal_renderer_opengl_utils_BucketDataType.Line.toString = $estr;
openfl__$internal_renderer_opengl_utils_BucketDataType.Line.__enum__ = openfl__$internal_renderer_opengl_utils_BucketDataType;
openfl__$internal_renderer_opengl_utils_BucketDataType.Fill = ["Fill",1];
openfl__$internal_renderer_opengl_utils_BucketDataType.Fill.toString = $estr;
openfl__$internal_renderer_opengl_utils_BucketDataType.Fill.__enum__ = openfl__$internal_renderer_opengl_utils_BucketDataType;
var openfl__$internal_renderer_opengl_utils_GLGraphicsData = function() { };
$hxClasses["openfl._internal.renderer.opengl.utils.GLGraphicsData"] = openfl__$internal_renderer_opengl_utils_GLGraphicsData;
openfl__$internal_renderer_opengl_utils_GLGraphicsData.__name__ = true;
var openfl__$internal_renderer_opengl_utils_GraphicType = $hxClasses["openfl._internal.renderer.opengl.utils.GraphicType"] = { __ename__ : true, __constructs__ : ["Polygon","Rectangle","Circle","Ellipse","DrawTriangles","DrawTiles","OverrideMatrix"] };
openfl__$internal_renderer_opengl_utils_GraphicType.Polygon = ["Polygon",0];
openfl__$internal_renderer_opengl_utils_GraphicType.Polygon.toString = $estr;
openfl__$internal_renderer_opengl_utils_GraphicType.Polygon.__enum__ = openfl__$internal_renderer_opengl_utils_GraphicType;
openfl__$internal_renderer_opengl_utils_GraphicType.Rectangle = function(rounded) { var $x = ["Rectangle",1,rounded]; $x.__enum__ = openfl__$internal_renderer_opengl_utils_GraphicType; $x.toString = $estr; return $x; };
openfl__$internal_renderer_opengl_utils_GraphicType.Circle = ["Circle",2];
openfl__$internal_renderer_opengl_utils_GraphicType.Circle.toString = $estr;
openfl__$internal_renderer_opengl_utils_GraphicType.Circle.__enum__ = openfl__$internal_renderer_opengl_utils_GraphicType;
openfl__$internal_renderer_opengl_utils_GraphicType.Ellipse = ["Ellipse",3];
openfl__$internal_renderer_opengl_utils_GraphicType.Ellipse.toString = $estr;
openfl__$internal_renderer_opengl_utils_GraphicType.Ellipse.__enum__ = openfl__$internal_renderer_opengl_utils_GraphicType;
openfl__$internal_renderer_opengl_utils_GraphicType.DrawTriangles = function(vertices,indices,uvtData,culling,colors,blendMode) { var $x = ["DrawTriangles",4,vertices,indices,uvtData,culling,colors,blendMode]; $x.__enum__ = openfl__$internal_renderer_opengl_utils_GraphicType; $x.toString = $estr; return $x; };
openfl__$internal_renderer_opengl_utils_GraphicType.DrawTiles = function(sheet,tileData,smooth,flags,shader,count) { var $x = ["DrawTiles",5,sheet,tileData,smooth,flags,shader,count]; $x.__enum__ = openfl__$internal_renderer_opengl_utils_GraphicType; $x.toString = $estr; return $x; };
openfl__$internal_renderer_opengl_utils_GraphicType.OverrideMatrix = function(matrix) { var $x = ["OverrideMatrix",6,matrix]; $x.__enum__ = openfl__$internal_renderer_opengl_utils_GraphicType; $x.toString = $estr; return $x; };
var openfl__$internal_renderer_opengl_utils_PingPongTexture = function(gl,width,height,smoothing,powerOfTwo) {
	if(powerOfTwo == null) powerOfTwo = true;
	if(smoothing == null) smoothing = true;
	this.__swapped = false;
	this.powerOfTwo = true;
	this.useOldTexture = false;
	this.gl = gl;
	this.width = width;
	this.height = height;
	this.smoothing = smoothing;
	this.powerOfTwo = powerOfTwo;
	this.set_renderTexture(new openfl__$internal_renderer_opengl_utils_RenderTexture(gl,width,height,smoothing,powerOfTwo));
};
$hxClasses["openfl._internal.renderer.opengl.utils.PingPongTexture"] = openfl__$internal_renderer_opengl_utils_PingPongTexture;
openfl__$internal_renderer_opengl_utils_PingPongTexture.__name__ = true;
openfl__$internal_renderer_opengl_utils_PingPongTexture.prototype = {
	swap: function() {
		this.__swapped = !this.__swapped;
		if((this.__swapped?this.__texture1:this.__texture0) == null) this.set_renderTexture(new openfl__$internal_renderer_opengl_utils_RenderTexture(this.gl,this.width,this.height,this.smoothing,this.powerOfTwo));
	}
	,resize: function(width,height) {
		this.width = width;
		this.height = height;
		(this.__swapped?this.__texture1:this.__texture0).resize(width,height);
	}
	,get_renderTexture: function() {
		if(this.__swapped) return this.__texture1; else return this.__texture0;
	}
	,set_renderTexture: function(v) {
		if(this.__swapped) return this.__texture1 = v; else return this.__texture0 = v;
	}
	,get_oldRenderTexture: function() {
		if(this.__swapped) return this.__texture0; else return this.__texture1;
	}
	,set_oldRenderTexture: function(v) {
		if(this.__swapped) return this.__texture0 = v; else return this.__texture1 = v;
	}
	,get_framebuffer: function() {
		return (this.__swapped?this.__texture1:this.__texture0).frameBuffer;
	}
	,get_texture: function() {
		if(this.useOldTexture) return (this.__swapped?this.__texture0:this.__texture1).texture; else return (this.__swapped?this.__texture1:this.__texture0).texture;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_PingPongTexture
	,__properties__: {get_texture:"get_texture",get_framebuffer:"get_framebuffer",set_oldRenderTexture:"set_oldRenderTexture",get_oldRenderTexture:"get_oldRenderTexture",set_renderTexture:"set_renderTexture",get_renderTexture:"get_renderTexture"}
};
var openfl__$internal_renderer_opengl_utils_RenderTexture = function(gl,width,height,smoothing,powerOfTwo) {
	if(powerOfTwo == null) powerOfTwo = true;
	if(smoothing == null) smoothing = true;
	this.powerOfTwo = true;
	this.gl = gl;
	this.powerOfTwo = powerOfTwo;
	this.frameBuffer = gl.createFramebuffer();
	this.texture = gl.createTexture();
	gl.bindTexture(gl.TEXTURE_2D,this.texture);
	gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MAG_FILTER,smoothing?gl.LINEAR:gl.NEAREST);
	gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MIN_FILTER,smoothing?gl.LINEAR:gl.NEAREST);
	gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_S,gl.CLAMP_TO_EDGE);
	gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_T,gl.CLAMP_TO_EDGE);
	gl.bindFramebuffer(gl.FRAMEBUFFER,this.frameBuffer);
	gl.framebufferTexture2D(gl.FRAMEBUFFER,gl.COLOR_ATTACHMENT0,gl.TEXTURE_2D,this.texture,0);
	this.renderBuffer = gl.createRenderbuffer();
	gl.bindRenderbuffer(gl.RENDERBUFFER,this.renderBuffer);
	gl.framebufferRenderbuffer(gl.FRAMEBUFFER,gl.DEPTH_STENCIL_ATTACHMENT,gl.RENDERBUFFER,this.renderBuffer);
	this.resize(width,height);
};
$hxClasses["openfl._internal.renderer.opengl.utils.RenderTexture"] = openfl__$internal_renderer_opengl_utils_RenderTexture;
openfl__$internal_renderer_opengl_utils_RenderTexture.__name__ = true;
openfl__$internal_renderer_opengl_utils_RenderTexture.prototype = {
	clear: function(r,g,b,a,mask) {
		if(a == null) a = 0;
		if(b == null) b = 0;
		if(g == null) g = 0;
		if(r == null) r = 0;
		this.gl.clearColor(r,g,b,a);
		this.gl.clear(mask == null?this.gl.COLOR_BUFFER_BIT:mask);
	}
	,resize: function(width,height) {
		if(this.width == width && this.height == height) return;
		this.width = width;
		this.height = height;
		var pow2W = width;
		var pow2H = height;
		if(this.powerOfTwo) {
			pow2W = this.powOfTwo(width);
			pow2H = this.powOfTwo(height);
		}
		var lastW = this.__width;
		var lastH = this.__height;
		this.__width = pow2W;
		this.__height = pow2H;
		this.createUVs();
		if(lastW == pow2W && lastH == pow2H) return;
		this.gl.bindTexture(this.gl.TEXTURE_2D,this.texture);
		this.gl.texImage2D(this.gl.TEXTURE_2D,0,this.gl.RGBA,this.__width,this.__height,0,this.gl.RGBA,this.gl.UNSIGNED_BYTE,null);
		this.gl.bindRenderbuffer(this.gl.RENDERBUFFER,this.renderBuffer);
		this.gl.renderbufferStorage(this.gl.RENDERBUFFER,this.gl.DEPTH_STENCIL,this.__width,this.__height);
	}
	,createUVs: function() {
		if(this.__uvData == null) this.__uvData = new openfl_display_TextureUvs();
		var w = this.width / this.__width;
		var h = this.height / this.__height;
		this.__uvData.x0 = 0;
		this.__uvData.y0 = 0;
		this.__uvData.x1 = w;
		this.__uvData.y1 = 0;
		this.__uvData.x2 = w;
		this.__uvData.y2 = h;
		this.__uvData.x3 = 0;
		this.__uvData.y3 = h;
	}
	,powOfTwo: function(value) {
		var n = 1;
		while(n < value) n <<= 1;
		return n;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_RenderTexture
};
var openfl__$internal_renderer_opengl_utils_ShaderManager = function(gl) {
	this.setContext(gl);
};
$hxClasses["openfl._internal.renderer.opengl.utils.ShaderManager"] = openfl__$internal_renderer_opengl_utils_ShaderManager;
openfl__$internal_renderer_opengl_utils_ShaderManager.__name__ = true;
openfl__$internal_renderer_opengl_utils_ShaderManager.prototype = {
	setContext: function(gl) {
		this.gl = gl;
		this.defaultShader = new openfl__$internal_renderer_opengl_shaders2_DefaultShader(gl);
		this.fillShader = new openfl__$internal_renderer_opengl_shaders2_FillShader(gl);
		this.patternFillShader = new openfl__$internal_renderer_opengl_shaders2_PatternFillShader(gl);
		this.drawTrianglesShader = new openfl__$internal_renderer_opengl_shaders2_DrawTrianglesShader(gl);
		this.primitiveShader = new openfl__$internal_renderer_opengl_shaders2_PrimitiveShader(gl);
		this.setShader(this.defaultShader,true);
	}
	,setShader: function(shader,force) {
		if(force == null) force = false;
		if(shader == null) {
			this.currentShader = null;
			this.gl.useProgram(null);
			return true;
		}
		if(this.currentShader != null && !force && this.currentShader.ID == shader.ID) return false;
		this.currentShader = shader;
		this.gl.useProgram(shader.program);
		return true;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_ShaderManager
};
var openfl__$internal_renderer_opengl_utils_SpriteBatch = function(gl,maxSprites) {
	if(maxSprites == null) maxSprites = 2000;
	this.lastEnableColor = true;
	this.enableColor = true;
	this.attributes = [];
	this.writtenVertexBytes = 0;
	this.drawing = false;
	this.dirty = true;
	this.states = [];
	this.maxSprites = maxSprites;
	this.attributes.push(new openfl__$internal_renderer_opengl_utils_VertexAttribute(2,5126,false,"openfl_aPosition"));
	this.attributes.push(new openfl__$internal_renderer_opengl_utils_VertexAttribute(2,5126,false,"openfl_aTexCoord0"));
	this.attributes.push(new openfl__$internal_renderer_opengl_utils_VertexAttribute(4,5121,true,"openfl_aColor"));
	var array = [1,1,1,1];
	var this1;
	if(array != null) this1 = new Float32Array(array); else this1 = null;
	this.attributes[2].defaultValue = this1;
	this.maxElementsPerVertex = 0;
	var _g = 0;
	var _g1 = this.attributes;
	while(_g < _g1.length) {
		var a = _g1[_g];
		++_g;
		this.maxElementsPerVertex += Math.floor(a.components * a.getElementsBytes() / 4);
	}
	this.vertexArraySize = maxSprites * this.maxElementsPerVertex * 4 * 4;
	this.indexArraySize = maxSprites * 6;
	this.vertexArray = new openfl__$internal_renderer_opengl_utils_VertexArray(this.attributes,this.vertexArraySize,false);
	var buffer = this.vertexArray.buffer;
	var this2;
	if(buffer != null) this2 = new Float32Array(buffer,0); else this2 = null;
	this.positions = this2;
	var buffer1 = this.vertexArray.buffer;
	var this3;
	if(buffer1 != null) this3 = new Uint32Array(buffer1,0); else this3 = null;
	this.colors = this3;
	var elements = this.indexArraySize;
	var this4;
	if(elements != null) this4 = new Uint16Array(elements); else this4 = null;
	this.indices = this4;
	var i = 0;
	var j = 0;
	while(i < this.indexArraySize) {
		this.indices[i] = j;
		this.indices[i + 1] = j + 1;
		this.indices[i + 2] = j + 2;
		this.indices[i + 3] = j;
		this.indices[i + 4] = j + 2;
		this.indices[i + 5] = j + 3;
		i += 6;
		j += 4;
	}
	this.currentState = new openfl__$internal_renderer_opengl_utils__$SpriteBatch_State();
	this.dirty = true;
	this.drawing = false;
	this.batchedSprites = 0;
	this.setContext(gl);
};
$hxClasses["openfl._internal.renderer.opengl.utils.SpriteBatch"] = openfl__$internal_renderer_opengl_utils_SpriteBatch;
openfl__$internal_renderer_opengl_utils_SpriteBatch.__name__ = true;
openfl__$internal_renderer_opengl_utils_SpriteBatch.prototype = {
	begin: function(renderSession,clipRect) {
		this.renderSession = renderSession;
		this.shader = renderSession.shaderManager.defaultShader;
		this.drawing = true;
		this.start(clipRect);
	}
	,finish: function() {
		this.stop();
		this.clipRect = null;
		this.drawing = false;
	}
	,start: function(clipRect) {
		if(!this.drawing) this.stop();
		this.dirty = true;
		this.clipRect = clipRect;
	}
	,stop: function() {
		this.flush();
	}
	,renderBitmapData: function(bitmapData,smoothing,matrix,ct,alpha,blendMode,flashShader,pixelSnapping,bgra) {
		if(bgra == null) bgra = false;
		if(alpha == null) alpha = 1;
		if(bitmapData == null) return;
		var texture = bitmapData.getTexture(this.gl);
		if(this.batchedSprites >= this.maxSprites) this.flush();
		var uvs = bitmapData.__uvData;
		if(uvs == null) return;
		this.prepareShader(flashShader,bitmapData);
		var color = ((alpha * 255 | 0) & 255) << 24 | 16777215;
		this.enableColor = true;
		if(this.enableColor != this.lastEnableColor) {
			this.flush();
			this.lastEnableColor = this.enableColor;
		}
		this.attributes[2].enabled = this.lastEnableColor;
		this.elementsPerVertex = this.getElementsPerVertex();
		var index = this.batchedSprites * 4 * this.elementsPerVertex;
		this.fillVertices(index,bitmapData.width,bitmapData.height,matrix,uvs,color,pixelSnapping);
		this.setState(this.batchedSprites,texture,smoothing,blendMode,ct,flashShader,true);
		this.batchedSprites++;
	}
	,renderTiles: function(object,sheet,tileData,smooth,flags,flashShader,count) {
		if(count == null) count = -1;
		if(flags == null) flags = 0;
		if(smooth == null) smooth = false;
		var texture = sheet.__bitmap.getTexture(this.gl);
		if(texture == null) return;
		var useScale = (flags & 1) > 0;
		var useRotation = (flags & 2) > 0;
		var useTransform = (flags & 16) > 0;
		var useRGB = (flags & 4) > 0;
		var useAlpha = (flags & 8) > 0;
		var useRect = (flags & 32) > 0;
		var useOrigin = (flags & 64) > 0;
		var blendMode;
		var _g = flags & 983040;
		switch(_g) {
		case 65536:
			blendMode = openfl_display_BlendMode.ADD;
			break;
		case 131072:
			blendMode = openfl_display_BlendMode.MULTIPLY;
			break;
		case 262144:
			blendMode = openfl_display_BlendMode.SCREEN;
			break;
		case 524288:
			blendMode = openfl_display_BlendMode.SUBTRACT;
			break;
		default:
			var _g1 = flags & 15728640;
			switch(_g1) {
			case 1048576:
				blendMode = openfl_display_BlendMode.DARKEN;
				break;
			case 2097152:
				blendMode = openfl_display_BlendMode.LIGHTEN;
				break;
			case 4194304:
				blendMode = openfl_display_BlendMode.OVERLAY;
				break;
			case 8388608:
				blendMode = openfl_display_BlendMode.HARDLIGHT;
				break;
			default:
				var _g2 = flags & 251658240;
				switch(_g2) {
				case 16777216:
					blendMode = openfl_display_BlendMode.DIFFERENCE;
					break;
				case 33554432:
					blendMode = openfl_display_BlendMode.INVERT;
					break;
				default:
					blendMode = openfl_display_BlendMode.NORMAL;
				}
			}
		}
		if(useTransform) {
			useScale = false;
			useRotation = false;
		}
		var scaleIndex = 0;
		var rotationIndex = 0;
		var rgbIndex = 0;
		var alphaIndex = 0;
		var transformIndex = 0;
		var numValues = 3;
		if(useRect) if(useOrigin) numValues = 8; else numValues = 6;
		if(useScale) {
			scaleIndex = numValues;
			numValues++;
		}
		if(useRotation) {
			rotationIndex = numValues;
			numValues++;
		}
		if(useTransform) {
			transformIndex = numValues;
			numValues += 4;
		}
		if(useRGB) {
			rgbIndex = numValues;
			numValues += 3;
		}
		if(useAlpha) {
			alphaIndex = numValues;
			numValues++;
		}
		var totalCount = tileData.length;
		if(count >= 0 && totalCount > count) totalCount = count;
		var itemCount = Math.ceil(totalCount / numValues);
		var iIndex = 0;
		var tileID = -1;
		var rect = sheet.__rectTile;
		var tileUV = sheet.__rectUV;
		var center = sheet.__point;
		var x = 0.0;
		var y = 0.0;
		var alpha = 1.0;
		var tint = 16777215;
		var color = -1;
		var scale = 1.0;
		var rotation = 0.0;
		var cosTheta = 1.0;
		var sinTheta = 0.0;
		var a = 0.0;
		var b = 0.0;
		var c = 0.0;
		var d = 0.0;
		var tx = 0.0;
		var ty = 0.0;
		var ox = 0.0;
		var oy = 0.0;
		var oMatrix = object.__worldTransform;
		var bIndex = 0;
		var tMa = 1.0;
		var tMb = 0.0;
		var tMc = 0.0;
		var tMd = 1.0;
		var tMtx = 0.0;
		var tMty = 0.0;
		var oMa = oMatrix.a;
		var oMb = oMatrix.b;
		var oMc = oMatrix.c;
		var oMd = oMatrix.d;
		var oMtx = oMatrix.tx;
		var oMty = oMatrix.ty;
		var rx = 0.0;
		var ry = 0.0;
		var rw = 0.0;
		var rh = 0.0;
		var tuvx = 0.0;
		var tuvy = 0.0;
		var tuvw = 0.0;
		var tuvh = 0.0;
		this.enableColor = true;
		if(this.enableColor != this.lastEnableColor) {
			this.flush();
			this.lastEnableColor = this.enableColor;
		}
		this.attributes[2].enabled = this.lastEnableColor;
		this.elementsPerVertex = this.getElementsPerVertex();
		this.prepareShader(flashShader,null);
		while(iIndex < totalCount) {
			if(this.batchedSprites >= this.maxSprites) this.flush();
			x = tileData[iIndex];
			y = tileData[iIndex + 1];
			if(useRect) {
				tileID = -1;
				rect.x = tileData[iIndex + 2];
				rect.y = tileData[iIndex + 3];
				rect.width = tileData[iIndex + 4];
				rect.height = tileData[iIndex + 5];
				if(useOrigin) {
					center.x = tileData[iIndex + 6];
					center.y = tileData[iIndex + 7];
				} else {
					center.x = 0;
					center.y = 0;
				}
				rw = rect.width;
				rh = rect.height;
				tuvx = rect.get_left() / sheet.__bitmap.width;
				tuvy = rect.get_top() / sheet.__bitmap.height;
				tuvw = rect.get_right() / sheet.__bitmap.width;
				tuvh = rect.get_bottom() / sheet.__bitmap.height;
			} else {
				tileID = (tileData[iIndex + 2] == null?0:tileData[iIndex + 2]) | 0;
				rect = sheet.__tileRects[tileID];
				center = sheet.__centerPoints[tileID];
				tileUV = sheet.__tileUVs[tileID];
				rw = rect.width;
				rh = rect.height;
				tuvx = tileUV.x;
				tuvy = tileUV.y;
				tuvw = tileUV.width;
				tuvh = tileUV.height;
			}
			if(rect != null && rect.width > 0 && rect.height > 0 && center != null) {
				alpha = 1;
				tint = 16777215;
				scale = 1.0;
				rotation = 0.0;
				cosTheta = 1.0;
				sinTheta = 0.0;
				if(useAlpha) alpha = tileData[iIndex + alphaIndex] * object.__worldAlpha; else alpha = object.__worldAlpha;
				if(useRGB) tint = (tileData[iIndex + rgbIndex] * 255 | 0) << 16 | (tileData[iIndex + rgbIndex + 1] * 255 | 0) << 8 | (tileData[iIndex + rgbIndex + 2] * 255 | 0);
				if(useScale) scale = tileData[iIndex + scaleIndex];
				if(useRotation) {
					rotation = tileData[iIndex + rotationIndex];
					cosTheta = Math.cos(rotation);
					sinTheta = Math.sin(rotation);
				}
				if(useTransform) {
					a = tileData[iIndex + transformIndex];
					b = tileData[iIndex + transformIndex + 1];
					c = tileData[iIndex + transformIndex + 2];
					d = tileData[iIndex + transformIndex + 3];
				} else {
					a = scale * cosTheta;
					b = scale * sinTheta;
					c = -b;
					d = a;
				}
				ox = center.x * a + center.y * c;
				oy = center.x * b + center.y * d;
				tx = x - ox;
				ty = y - oy;
				tMa = (a * oMa + b * oMc) * rw;
				tMb = (a * oMb + b * oMd) * rw;
				tMc = (c * oMa + d * oMc) * rh;
				tMd = (c * oMb + d * oMd) * rh;
				tMtx = tx * oMa + ty * oMc + oMtx;
				tMty = tx * oMb + ty * oMd + oMty;
				bIndex = this.batchedSprites * 4 * this.elementsPerVertex;
				this.positions[bIndex] = tMtx;
				this.positions[bIndex + 1] = tMty;
				this.positions[bIndex + 5] = tMa + tMtx;
				this.positions[bIndex + 6] = tMb + tMty;
				this.positions[bIndex + 10] = tMa + tMc + tMtx;
				this.positions[bIndex + 11] = tMd + tMb + tMty;
				this.positions[bIndex + 15] = tMc + tMtx;
				this.positions[bIndex + 16] = tMd + tMty;
				var val;
				var val1;
				var val2 = this.colors[bIndex + 19] = ((alpha * 255 | 0) & 255) << 24 | tint;
				val1 = this.colors[bIndex + 14] = val2;
				val = this.colors[bIndex + 9] = val1;
				this.colors[bIndex + 4] = val;
				this.positions[bIndex + 2] = tuvx;
				this.positions[bIndex + 3] = tuvy;
				this.positions[bIndex + 7] = tuvw;
				this.positions[bIndex + 8] = tuvy;
				this.positions[bIndex + 12] = tuvw;
				this.positions[bIndex + 13] = tuvh;
				this.positions[bIndex + 17] = tuvx;
				this.positions[bIndex + 18] = tuvh;
				this.writtenVertexBytes = bIndex + 20;
				this.setState(this.batchedSprites,texture,smooth,blendMode,object.__worldColorTransform,flashShader,false);
				this.batchedSprites++;
			}
			iIndex += numValues;
		}
	}
	,fillVertices: function(index,width,height,matrix,uvs,color,pixelSnapping) {
		if(color == null) color = -1;
		var a = matrix.a;
		var b = matrix.b;
		var c = matrix.c;
		var d = matrix.d;
		var tx = matrix.tx;
		var ty = matrix.ty;
		if(pixelSnapping == null || pixelSnapping == openfl_display_PixelSnapping.NEVER) {
			this.positions[index] = tx;
			this.positions[index + 1] = ty;
			this.positions[index + 5] = a * width + tx;
			this.positions[index + 6] = b * width + ty;
			this.positions[index + 10] = a * width + c * height + tx;
			this.positions[index + 11] = d * height + b * width + ty;
			this.positions[index + 15] = c * height + tx;
			this.positions[index + 16] = d * height + ty;
		} else {
			var val = Math.round(tx);
			this.positions[index] = val;
			var val1 = Math.round(ty);
			this.positions[index + 1] = val1;
			var val2 = Math.round(a * width + tx);
			this.positions[index + 5] = val2;
			var val3 = Math.round(b * width + ty);
			this.positions[index + 6] = val3;
			var val4 = Math.round(a * width + c * height + tx);
			this.positions[index + 10] = val4;
			var val5 = Math.round(d * height + b * width + ty);
			this.positions[index + 11] = val5;
			var val6 = Math.round(c * height + tx);
			this.positions[index + 15] = val6;
			var val7 = Math.round(d * height + ty);
			this.positions[index + 16] = val7;
		}
		if(this.enableColor) {
			var val8;
			var val9;
			var val10 = this.colors[index + 19] = color;
			val9 = this.colors[index + 14] = val10;
			val8 = this.colors[index + 9] = val9;
			this.colors[index + 4] = val8;
		}
		this.positions[index + 2] = uvs.x0;
		this.positions[index + 3] = uvs.y0;
		this.positions[index + 7] = uvs.x1;
		this.positions[index + 8] = uvs.y1;
		this.positions[index + 12] = uvs.x2;
		this.positions[index + 13] = uvs.y2;
		this.positions[index + 17] = uvs.x3;
		this.positions[index + 18] = uvs.y3;
		this.writtenVertexBytes = index + 20;
	}
	,flush: function() {
		if(this.batchedSprites == 0) return;
		if(this.clipRect != null) {
			this.gl.enable(this.gl.SCISSOR_TEST);
			this.gl.scissor(Math.floor(this.clipRect.x),Math.floor(this.clipRect.y),Math.ceil(this.clipRect.width),Math.ceil(this.clipRect.height));
		}
		if(this.dirty) {
			this.dirty = false;
			this.renderSession.activeTextures = 1;
			this.vertexArray.bind();
			this.gl.bindBuffer(this.gl.ELEMENT_ARRAY_BUFFER,this.indexBuffer);
		}
		if(this.writtenVertexBytes > this.vertexArraySize * 0.5) this.vertexArray.upload(this.positions); else {
			var view = this.positions.subarray(0,this.writtenVertexBytes);
			this.vertexArray.upload(view);
		}
		var nextState;
		var batchSize = 0;
		var start = 0;
		this.currentState.shader = null;
		this.currentState.shaderData = null;
		this.currentState.texture = null;
		this.currentState.textureSmooth = false;
		this.currentState.blendMode = this.renderSession.blendModeManager.currentBlendMode;
		this.currentState.colorTransform = null;
		this.currentState.skipColorTransformAlpha = false;
		var _g1 = 0;
		var _g = this.batchedSprites;
		while(_g1 < _g) {
			var i = _g1++;
			nextState = this.states[i];
			this.currentState.skipColorTransformAlpha = nextState.skipColorTransformAlpha;
			if(!nextState.equals(this.currentState)) {
				this.renderBatch(this.currentState,batchSize,start);
				start = i;
				batchSize = 0;
				this.currentState.shader = nextState.shader;
				this.currentState.shaderData = nextState.shaderData;
				this.currentState.texture = nextState.texture;
				this.currentState.textureSmooth = nextState.textureSmooth;
				this.currentState.blendMode = nextState.blendMode;
				this.currentState.colorTransform = nextState.colorTransform;
			}
			batchSize++;
		}
		this.renderBatch(this.currentState,batchSize,start);
		this.batchedSprites = 0;
		this.writtenVertexBytes = 0;
		if(this.clipRect != null) this.gl.disable(this.gl.SCISSOR_TEST);
	}
	,renderBatch: function(state,size,start) {
		if(size == 0 || state.texture == null) return;
		var shader;
		if(state.shader == null) shader = this.renderSession.shaderManager.defaultShader; else shader = state.shader;
		this.renderSession.shaderManager.setShader(shader);
		shader.bindVertexArray(this.vertexArray);
		this.renderSession.blendModeManager.setBlendMode(shader.blendMode != null?shader.blendMode:state.blendMode);
		this.gl.uniformMatrix3fv(shader.getUniformLocation("openfl_uProjectionMatrix"),false,this.renderSession.projectionMatrix.toArray(true));
		if(state.colorTransform != null) {
			this.gl.uniform1i(shader.getUniformLocation("openfl_uUseColorTransform"),1);
			var ct = state.colorTransform;
			this.gl.uniform4f(shader.getUniformLocation("openfl_uColorMultiplier"),ct.redMultiplier,ct.greenMultiplier,ct.blueMultiplier,state.skipColorTransformAlpha?1:ct.alphaMultiplier);
			this.gl.uniform4f(shader.getUniformLocation("openfl_uColorOffset"),ct.redOffset / 255.,ct.greenOffset / 255.,ct.blueOffset / 255.,ct.alphaOffset / 255.);
		} else {
			this.gl.uniform1i(shader.getUniformLocation("openfl_uUseColorTransform"),0);
			this.gl.uniform4f(shader.getUniformLocation("openfl_uColorMultiplier"),1,1,1,1);
			this.gl.uniform4f(shader.getUniformLocation("openfl_uColorOffset"),0,0,0,0);
		}
		this.gl.activeTexture(this.gl.TEXTURE0);
		this.gl.bindTexture(this.gl.TEXTURE_2D,state.texture);
		this.gl.uniform1i(shader.getUniformLocation("openfl_uSampler0"),0);
		if(shader.smooth != null && shader.smooth || state.textureSmooth) {
			this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_MAG_FILTER,this.gl.LINEAR);
			this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_MIN_FILTER,this.gl.LINEAR);
		} else {
			this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_MAG_FILTER,this.gl.NEAREST);
			this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_MIN_FILTER,this.gl.NEAREST);
		}
		this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_WRAP_S,shader.wrapS);
		this.gl.texParameteri(this.gl.TEXTURE_2D,this.gl.TEXTURE_WRAP_T,shader.wrapT);
		shader.applyData(state.shaderData,this.renderSession);
		this.gl.drawElements(this.gl.TRIANGLES,size * 6,this.gl.UNSIGNED_SHORT,start * 6 * 2);
		this.renderSession.drawCount++;
	}
	,setState: function(index,texture,smooth,blendMode,colorTransform,shader,skipAlpha) {
		if(skipAlpha == null) skipAlpha = false;
		if(smooth == null) smooth = false;
		var state = this.states[index];
		if(state == null) state = this.states[index] = new openfl__$internal_renderer_opengl_utils__$SpriteBatch_State();
		state.texture = texture;
		state.textureSmooth = smooth;
		state.blendMode = blendMode;
		if(colorTransform != null && colorTransform.__isDefault()) state.colorTransform = null; else state.colorTransform = colorTransform;
		state.skipColorTransformAlpha = skipAlpha;
		if(shader == null) {
			state.shader = null;
			state.shaderData = null;
		} else {
			state.shader = shader.__shader;
			state.shaderData = shader.data;
		}
	}
	,setContext: function(gl) {
		this.gl = gl;
		this.vertexArray.setContext(gl,this.positions);
		this.indexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,this.indexBuffer);
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER,this.indices,gl.STATIC_DRAW);
	}
	,prepareShader: function(flashShader,bd) {
		if(flashShader != null) {
			flashShader.__init(this.gl);
			flashShader.__shader.wrapS = flashShader.repeatX;
			flashShader.__shader.wrapT = flashShader.repeatY;
			flashShader.__shader.smooth = flashShader.smooth;
			flashShader.__shader.blendMode = flashShader.blendMode;
			var objSize = flashShader.data.get(openfl_display_Shader.uObjectSize);
			var texSize = flashShader.data.get(openfl_display_Shader.uTextureSize);
			if(bd != null) {
				objSize.value[0] = bd.width;
				objSize.value[1] = bd.height;
				if(bd.__pingPongTexture != null) {
					texSize.value[0] = bd.__pingPongTexture.get_renderTexture().__width;
					texSize.value[1] = bd.__pingPongTexture.get_renderTexture().__height;
				} else {
					texSize.value[0] = bd.width;
					texSize.value[1] = bd.height;
				}
			} else {
				objSize.value[0] = 0;
				objSize.value[1] = 0;
				texSize.value[0] = 0;
				texSize.value[1] = 0;
			}
		}
	}
	,getElementsPerVertex: function() {
		var r = 0;
		var _g = 0;
		var _g1 = this.attributes;
		while(_g < _g1.length) {
			var a = _g1[_g];
			++_g;
			if(a.enabled) r += Math.floor(a.components * a.getElementsBytes() / 4);
		}
		return r;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_SpriteBatch
};
var openfl__$internal_renderer_opengl_utils__$SpriteBatch_State = function() {
	this.skipColorTransformAlpha = false;
	this.textureSmooth = true;
};
$hxClasses["openfl._internal.renderer.opengl.utils._SpriteBatch.State"] = openfl__$internal_renderer_opengl_utils__$SpriteBatch_State;
openfl__$internal_renderer_opengl_utils__$SpriteBatch_State.__name__ = true;
openfl__$internal_renderer_opengl_utils__$SpriteBatch_State.prototype = {
	equals: function(other) {
		return (this.shader == null && other.shader == null || this.shader != null && other.shader != null && this.shader.ID == other.shader.ID) && this.texture == other.texture && this.textureSmooth == other.textureSmooth && this.blendMode == other.blendMode && (this.colorTransform == null && other.colorTransform == null || this.colorTransform != null && other.colorTransform != null && this.colorTransform.__equals(other.colorTransform,this.skipColorTransformAlpha));
	}
	,__class__: openfl__$internal_renderer_opengl_utils__$SpriteBatch_State
};
var openfl__$internal_renderer_opengl_utils_StencilManager = function(gl) {
	this.stencilMask = 0;
	this.stencilStack = [];
	this.setContext(gl);
	this.reverse = true;
	this.count = 0;
};
$hxClasses["openfl._internal.renderer.opengl.utils.StencilManager"] = openfl__$internal_renderer_opengl_utils_StencilManager;
openfl__$internal_renderer_opengl_utils_StencilManager.__name__ = true;
openfl__$internal_renderer_opengl_utils_StencilManager.prototype = {
	prepareGraphics: function(fill,renderSession,translationMatrix) {
		var shader = renderSession.shaderManager.fillShader;
		renderSession.shaderManager.setShader(shader);
		this.gl.uniformMatrix3fv(shader.getUniformLocation("openfl_uTranslationMatrix"),false,translationMatrix);
		this.gl.uniformMatrix3fv(shader.getUniformLocation("openfl_uProjectionMatrix"),false,renderSession.projectionMatrix.toArray(true));
		fill.vertexArray.bind();
		shader.bindVertexArray(fill.vertexArray);
		this.gl.bindBuffer(this.gl.ELEMENT_ARRAY_BUFFER,fill.indexBuffer);
	}
	,pushBucket: function(bucket,renderSession,translationMatrix,isMask) {
		if(isMask == null) isMask = false;
		if(!isMask) {
			this.gl.enable(this.gl.STENCIL_TEST);
			this.gl.clear(this.gl.STENCIL_BUFFER_BIT);
			this.gl.stencilMask(255);
			this.gl.colorMask(false,false,false,false);
			this.gl.stencilFunc(this.gl.NEVER,1,255);
			this.gl.stencilOp(this.gl.INVERT,this.gl.KEEP,this.gl.KEEP);
			this.gl.clear(this.gl.STENCIL_BUFFER_BIT);
		}
		var _g = 0;
		var _g1 = bucket.fills;
		while(_g < _g1.length) {
			var fill = _g1[_g];
			++_g;
			if(fill.available) continue;
			this.prepareGraphics(fill,renderSession,translationMatrix);
			this.gl.drawElements(fill.drawMode,fill.glIndices.length,this.gl.UNSIGNED_SHORT,0);
		}
		if(!isMask) {
			this.gl.colorMask(true,true,true,renderSession.renderer.transparent);
			this.gl.stencilOp(this.gl.KEEP,this.gl.KEEP,this.gl.KEEP);
			this.gl.stencilFunc(this.gl.EQUAL,255,255);
		}
	}
	,popBucket: function(object,bucket,renderSession) {
		this.gl.disable(this.gl.STENCIL_TEST);
	}
	,pushMask: function(object,renderSession) {
		var maskGraphics = object.__maskGraphics;
		if(maskGraphics == null || maskGraphics.__commands.get_length() <= 0) return;
		if(this.stencilMask == 0) {
			this.gl.enable(this.gl.STENCIL_TEST);
			this.gl.clear(this.gl.STENCIL_BUFFER_BIT);
		}
		this.stencilMask++;
		if(maskGraphics.__dirty) openfl__$internal_renderer_opengl_utils_GraphicsRenderer.updateGraphics(object,maskGraphics,renderSession.gl);
		var func;
		if(this.stencilMask == 1) func = this.gl.NEVER; else func = this.gl.EQUAL;
		var ref = this.stencilMask;
		var mask = 255 - this.stencilMask;
		this.gl.stencilMask(255);
		this.gl.colorMask(false,false,false,false);
		this.gl.stencilFunc(func,ref,mask);
		this.gl.stencilOp(this.gl.REPLACE,this.gl.KEEP,this.gl.KEEP);
		var glStack = maskGraphics.__glStack[openfl__$internal_renderer_opengl_GLRenderer.glContextId];
		var bucket;
		var translationMatrix = object.__worldTransform;
		var _g1 = 0;
		var _g = glStack.buckets.length;
		while(_g1 < _g) {
			var i = _g1++;
			bucket = glStack.buckets[i];
			if(bucket.overrideMatrix != null) translationMatrix = bucket.overrideMatrix; else translationMatrix = object.__worldTransform;
			var _g2 = bucket.mode;
			switch(_g2[1]) {
			case 1:case 2:
				this.pushBucket(bucket,renderSession,translationMatrix.toArray(true),true);
				break;
			default:
			}
		}
		this.gl.colorMask(true,true,true,renderSession.renderer.transparent);
		this.gl.stencilOp(this.gl.KEEP,this.gl.KEEP,this.gl.KEEP);
		this.gl.stencilFunc(this.gl.EQUAL,this.stencilMask,255);
	}
	,popMask: function(object,renderSession) {
		this.stencilMask--;
		if(this.stencilMask <= 0) {
			this.gl.disable(this.gl.STENCIL_TEST);
			this.stencilMask = 0;
		}
	}
	,setContext: function(gl) {
		this.gl = gl;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_StencilManager
};
var openfl__$internal_renderer_opengl_utils_VertexArray = function(attributes,size,isStatic) {
	if(isStatic == null) isStatic = false;
	if(size == null) size = 0;
	this.isStatic = false;
	this.size = 0;
	this.attributes = [];
	this.size = size;
	this.attributes = attributes;
	if(size > 0) this.buffer = new ArrayBuffer(size);
	this.isStatic = isStatic;
};
$hxClasses["openfl._internal.renderer.opengl.utils.VertexArray"] = openfl__$internal_renderer_opengl_utils_VertexArray;
openfl__$internal_renderer_opengl_utils_VertexArray.__name__ = true;
openfl__$internal_renderer_opengl_utils_VertexArray.prototype = {
	bind: function() {
		this.gl.bindBuffer(this.gl.ARRAY_BUFFER,this.glBuffer);
	}
	,upload: function(view) {
		this.gl.bufferSubData(this.gl.ARRAY_BUFFER,0,view);
	}
	,setContext: function(gl,view) {
		this.gl = gl;
		this.glBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER,this.glBuffer);
		gl.bufferData(gl.ARRAY_BUFFER,view,this.isStatic?gl.STATIC_DRAW:gl.DYNAMIC_DRAW);
	}
	,get_stride: function() {
		var s = 0;
		var _g = 0;
		var _g1 = this.attributes;
		while(_g < _g1.length) {
			var a = _g1[_g];
			++_g;
			if(a.enabled) s += Math.floor(a.components * a.getElementsBytes() / 4) * 4;
		}
		return s;
	}
	,__class__: openfl__$internal_renderer_opengl_utils_VertexArray
	,__properties__: {get_stride:"get_stride"}
};
var openfl__$internal_text_TextEngine = function(textField) {
	this.textField = textField;
	this.width = 100;
	this.height = 100;
	this.text = "";
	this.bounds = new openfl_geom_Rectangle(0,0,0,0);
	this.type = openfl_text_TextFieldType.DYNAMIC;
	this.autoSize = openfl_text_TextFieldAutoSize.NONE;
	this.displayAsPassword = false;
	this.embedFonts = false;
	this.selectable = true;
	this.borderColor = 0;
	this.border = false;
	this.backgroundColor = 16777215;
	this.background = false;
	this.gridFitType = openfl_text_GridFitType.PIXEL;
	this.maxChars = 0;
	this.multiline = false;
	this.sharpness = 0;
	this.scrollH = 0;
	this.scrollV = 1;
	this.wordWrap = false;
	this.lineAscents = [];
	this.lineBreaks = [];
	this.lineDescents = [];
	this.lineLeadings = [];
	this.lineHeights = [];
	this.lineWidths = [];
	this.layoutGroups = [];
	this.textFormatRanges = [];
	openfl__$internal_text_TextEngine.__canvas = window.document.createElement("canvas");
	openfl__$internal_text_TextEngine.__context = openfl__$internal_text_TextEngine.__canvas.getContext("2d");
};
$hxClasses["openfl._internal.text.TextEngine"] = openfl__$internal_text_TextEngine;
openfl__$internal_text_TextEngine.__name__ = true;
openfl__$internal_text_TextEngine.__canvas = null;
openfl__$internal_text_TextEngine.__context = null;
openfl__$internal_text_TextEngine.getFont = function(format) {
	var font;
	if(format.italic) font = "italic "; else font = "normal ";
	font += "normal ";
	if(format.bold) font += "bold "; else font += "normal ";
	font += format.size + "px";
	font += "/" + (format.size + format.leading + 6) + "px ";
	font += "" + (function($this) {
		var $r;
		var _g = format.font;
		$r = (function($this) {
			var $r;
			switch(_g) {
			case "_sans":
				$r = "sans-serif";
				break;
			case "_serif":
				$r = "serif";
				break;
			case "_typewriter":
				$r = "monospace";
				break;
			default:
				$r = "'" + format.font + "'";
			}
			return $r;
		}($this));
		return $r;
	}(this));
	return font;
};
openfl__$internal_text_TextEngine.prototype = {
	getBounds: function() {
		var padding;
		if(this.border) padding = 1; else padding = 0;
		this.bounds.width = this.width + padding;
		this.bounds.height = this.height + padding;
	}
	,getLineMeasurements: function() {
		this.lineAscents.splice(0,this.lineAscents.length);
		this.lineDescents.splice(0,this.lineDescents.length);
		this.lineLeadings.splice(0,this.lineLeadings.length);
		this.lineHeights.splice(0,this.lineHeights.length);
		this.lineWidths.splice(0,this.lineWidths.length);
		var currentLineAscent = 0.0;
		var currentLineDescent = 0.0;
		var currentLineLeading = null;
		var currentLineHeight = 0.0;
		var currentLineWidth = 0.0;
		this.textWidth = 0;
		this.textHeight = 0;
		this.numLines = 1;
		this.bottomScrollV = 0;
		this.maxScrollH = 0;
		var _g = 0;
		var _g1 = this.layoutGroups;
		while(_g < _g1.length) {
			var group = _g1[_g];
			++_g;
			while(group.lineIndex > this.numLines - 1) {
				this.lineAscents.push(currentLineAscent);
				this.lineDescents.push(currentLineDescent);
				this.lineLeadings.push(currentLineLeading != null?currentLineLeading:0);
				this.lineHeights.push(currentLineHeight);
				this.lineWidths.push(currentLineWidth);
				currentLineAscent = 0;
				currentLineDescent = 0;
				currentLineLeading = null;
				currentLineHeight = 0;
				currentLineWidth = 0;
				this.numLines++;
				if(this.textHeight <= this.height - 2) this.bottomScrollV++;
			}
			currentLineAscent = Math.max(currentLineAscent,group.ascent);
			currentLineDescent = Math.max(currentLineDescent,group.descent);
			if(currentLineLeading == null) currentLineLeading = group.leading; else currentLineLeading = Std["int"](Math.max(currentLineLeading,group.leading));
			currentLineHeight = Math.max(currentLineHeight,group.height);
			currentLineWidth = group.offsetX - 2 + group.width;
			if(currentLineWidth > this.textWidth) this.textWidth = currentLineWidth;
			this.textHeight = group.offsetY - 2 + group.ascent + group.descent;
		}
		this.lineAscents.push(currentLineAscent);
		this.lineDescents.push(currentLineDescent);
		this.lineLeadings.push(currentLineLeading != null?currentLineLeading:0);
		this.lineHeights.push(currentLineHeight);
		this.lineWidths.push(currentLineWidth);
		if(this.numLines == 1) {
			this.bottomScrollV = 1;
			if(currentLineLeading > 0) this.textHeight += currentLineLeading;
		} else if(this.textHeight <= this.height - 2) this.bottomScrollV++;
		if(this.textWidth > this.width - 4) this.maxScrollH = this.textWidth - this.width + 4 | 0; else this.maxScrollH = 0;
		this.maxScrollV = this.numLines - this.bottomScrollV + 1;
	}
	,getLayoutGroups: function() {
		var _g = this;
		this.layoutGroups.splice(0,this.layoutGroups.length);
		var rangeIndex = -1;
		var formatRange = null;
		var font = null;
		var currentFormat = openfl_text_TextField.__defaultTextFormat.clone();
		var leading = 0;
		var ascent = 0.0;
		var descent = 0.0;
		var layoutGroup;
		var advances;
		var widthValue;
		var heightValue = 0.0;
		var spaceWidth = 0.0;
		var previousSpaceIndex = 0;
		var spaceIndex = this.text.indexOf(" ");
		var breakIndex = this.text.indexOf("\n");
		var marginRight = 0.0;
		var offsetX = 2.0;
		var offsetY = 2.0;
		var textIndex = 0;
		var lineIndex = 0;
		var lineFormat = null;
		if(rangeIndex < _g.textFormatRanges.length - 1) {
			rangeIndex++;
			formatRange = _g.textFormatRanges[rangeIndex];
			currentFormat.__merge(formatRange.format);
			openfl__$internal_text_TextEngine.__context.font = openfl__$internal_text_TextEngine.getFont(currentFormat);
			ascent = currentFormat.size;
			descent = currentFormat.size * 0.185;
			leading = currentFormat.leading;
			heightValue = ascent + descent + leading;
			if(spaceIndex > -1) spaceWidth = openfl__$internal_text_TextEngine.__context.measureText(" ").width;
		}
		lineFormat = formatRange.format;
		var wrap;
		while(textIndex < this.text.length) if(breakIndex > -1 && (spaceIndex == -1 || breakIndex < spaceIndex) && formatRange.end >= breakIndex) {
			layoutGroup = new openfl__$internal_text_TextLayoutGroup(formatRange.format,textIndex,breakIndex);
			var text = this.text;
			var advances1 = [];
			var _g1 = textIndex;
			while(_g1 < breakIndex) {
				var i = _g1++;
				advances1.push(openfl__$internal_text_TextEngine.__context.measureText(text.charAt(i)).width);
			}
			layoutGroup.advances = advances1;
			layoutGroup.offsetX = offsetX;
			layoutGroup.ascent = ascent;
			layoutGroup.descent = descent;
			layoutGroup.leading = leading;
			layoutGroup.lineIndex = lineIndex;
			layoutGroup.offsetY = offsetY;
			var advances2 = layoutGroup.advances;
			var width = 0.0;
			var _g2 = 0;
			while(_g2 < advances2.length) {
				var advance = advances2[_g2];
				++_g2;
				width += advance;
			}
			layoutGroup.width = width;
			layoutGroup.height = heightValue;
			this.layoutGroups.push(layoutGroup);
			offsetY += heightValue;
			offsetX = 2;
			if(this.wordWrap && layoutGroup.offsetX + layoutGroup.width > this.width - 2) {
				layoutGroup.offsetY = offsetY;
				layoutGroup.offsetX = offsetX;
				offsetY += heightValue;
				lineIndex++;
			}
			textIndex = breakIndex + 1;
			breakIndex = this.text.indexOf("\n",textIndex);
			lineIndex++;
			if(formatRange.end == breakIndex) {
				if(rangeIndex < _g.textFormatRanges.length - 1) {
					rangeIndex++;
					formatRange = _g.textFormatRanges[rangeIndex];
					currentFormat.__merge(formatRange.format);
					openfl__$internal_text_TextEngine.__context.font = openfl__$internal_text_TextEngine.getFont(currentFormat);
					ascent = currentFormat.size;
					descent = currentFormat.size * 0.185;
					leading = currentFormat.leading;
					heightValue = ascent + descent + leading;
					if(spaceIndex > -1) spaceWidth = openfl__$internal_text_TextEngine.__context.measureText(" ").width;
				}
				lineFormat = formatRange.format;
			}
		} else if(formatRange.end >= spaceIndex && spaceIndex > -1) {
			layoutGroup = null;
			wrap = false;
			while(true) {
				if(spaceIndex == -1) spaceIndex = formatRange.end;
				var text1 = this.text;
				var advances3 = [];
				var _g3 = textIndex;
				while(_g3 < spaceIndex) {
					var i1 = _g3++;
					advances3.push(openfl__$internal_text_TextEngine.__context.measureText(text1.charAt(i1)).width);
				}
				advances = advances3;
				var width1 = 0.0;
				var _g4 = 0;
				while(_g4 < advances.length) {
					var advance1 = advances[_g4];
					++_g4;
					width1 += advance1;
				}
				widthValue = width1;
				if(this.wordWrap) {
					if(offsetX + widthValue > this.width - 2) wrap = true;
				}
				if(wrap) {
					offsetY += heightValue;
					var i2 = this.layoutGroups.length - 1;
					var offsetCount = 0;
					while(true) {
						layoutGroup = this.layoutGroups[i2];
						if(i2 > 0 && layoutGroup.startIndex > previousSpaceIndex) offsetCount++; else break;
						i2--;
					}
					lineIndex++;
					offsetX = 2;
					if(offsetCount > 0) {
						var bumpX = this.layoutGroups[this.layoutGroups.length - offsetCount].offsetX;
						var _g11 = this.layoutGroups.length - offsetCount;
						var _g5 = this.layoutGroups.length;
						while(_g11 < _g5) {
							var i3 = _g11++;
							layoutGroup = this.layoutGroups[i3];
							layoutGroup.offsetX -= bumpX;
							layoutGroup.offsetY = offsetY;
							layoutGroup.lineIndex = lineIndex;
							offsetX += layoutGroup.width;
						}
					}
					layoutGroup = new openfl__$internal_text_TextLayoutGroup(formatRange.format,textIndex,spaceIndex);
					layoutGroup.advances = advances;
					layoutGroup.offsetX = offsetX;
					layoutGroup.ascent = ascent;
					layoutGroup.descent = descent;
					layoutGroup.leading = leading;
					layoutGroup.lineIndex = lineIndex;
					layoutGroup.offsetY = offsetY;
					layoutGroup.width = widthValue;
					layoutGroup.height = heightValue;
					this.layoutGroups.push(layoutGroup);
					offsetX = widthValue + spaceWidth;
					marginRight = spaceWidth;
					wrap = false;
				} else {
					if(layoutGroup != null && textIndex == spaceIndex) {
						if(formatRange.format.align != openfl_text_TextFormatAlign.JUSTIFY) layoutGroup.endIndex = spaceIndex;
						layoutGroup.advances.push(spaceWidth);
						marginRight += spaceWidth;
					} else if(layoutGroup == null || lineFormat.align == openfl_text_TextFormatAlign.JUSTIFY) {
						layoutGroup = new openfl__$internal_text_TextLayoutGroup(formatRange.format,textIndex,spaceIndex);
						layoutGroup.advances = advances;
						layoutGroup.offsetX = offsetX;
						layoutGroup.ascent = ascent;
						layoutGroup.descent = descent;
						layoutGroup.leading = leading;
						layoutGroup.lineIndex = lineIndex;
						layoutGroup.offsetY = offsetY;
						layoutGroup.width = widthValue;
						layoutGroup.height = heightValue;
						this.layoutGroups.push(layoutGroup);
						layoutGroup.advances.push(spaceWidth);
						marginRight = spaceWidth;
					} else {
						layoutGroup.endIndex = spaceIndex;
						layoutGroup.advances = layoutGroup.advances.concat(advances);
						layoutGroup.width += marginRight + widthValue;
						layoutGroup.advances.push(spaceWidth);
						marginRight = spaceWidth;
					}
					offsetX += widthValue + spaceWidth;
				}
				textIndex = spaceIndex + 1;
				previousSpaceIndex = spaceIndex;
				spaceIndex = this.text.indexOf(" ",previousSpaceIndex + 1);
				if(formatRange.end <= previousSpaceIndex) {
					layoutGroup = null;
					if(rangeIndex < _g.textFormatRanges.length - 1) {
						rangeIndex++;
						formatRange = _g.textFormatRanges[rangeIndex];
						currentFormat.__merge(formatRange.format);
						openfl__$internal_text_TextEngine.__context.font = openfl__$internal_text_TextEngine.getFont(currentFormat);
						ascent = currentFormat.size;
						descent = currentFormat.size * 0.185;
						leading = currentFormat.leading;
						heightValue = ascent + descent + leading;
						if(spaceIndex > -1) spaceWidth = openfl__$internal_text_TextEngine.__context.measureText(" ").width;
					}
				}
				if(spaceIndex > breakIndex && breakIndex > -1 || textIndex > this.text.length || spaceIndex > formatRange.end || spaceIndex == -1 && breakIndex > -1) break;
			}
		} else {
			if(textIndex >= formatRange.end) break;
			layoutGroup = new openfl__$internal_text_TextLayoutGroup(formatRange.format,textIndex,formatRange.end);
			var text2 = this.text;
			var advances4 = [];
			var _g6 = textIndex;
			while(_g6 < formatRange.end) {
				var i4 = _g6++;
				advances4.push(openfl__$internal_text_TextEngine.__context.measureText(text2.charAt(i4)).width);
			}
			layoutGroup.advances = advances4;
			layoutGroup.offsetX = offsetX;
			layoutGroup.ascent = ascent;
			layoutGroup.descent = descent;
			layoutGroup.leading = leading;
			layoutGroup.lineIndex = lineIndex;
			layoutGroup.offsetY = offsetY;
			var advances5 = layoutGroup.advances;
			var width2 = 0.0;
			var _g7 = 0;
			while(_g7 < advances5.length) {
				var advance2 = advances5[_g7];
				++_g7;
				width2 += advance2;
			}
			layoutGroup.width = width2;
			layoutGroup.height = heightValue;
			this.layoutGroups.push(layoutGroup);
			offsetX += layoutGroup.width;
			textIndex = formatRange.end;
			if(rangeIndex < _g.textFormatRanges.length - 1) {
				rangeIndex++;
				formatRange = _g.textFormatRanges[rangeIndex];
				currentFormat.__merge(formatRange.format);
				openfl__$internal_text_TextEngine.__context.font = openfl__$internal_text_TextEngine.getFont(currentFormat);
				ascent = currentFormat.size;
				descent = currentFormat.size * 0.185;
				leading = currentFormat.leading;
				heightValue = ascent + descent + leading;
				if(spaceIndex > -1) spaceWidth = openfl__$internal_text_TextEngine.__context.measureText(" ").width;
			}
		}
	}
	,setTextAlignment: function() {
		var lineIndex = -1;
		var offsetX = 0.0;
		var group;
		var lineLength;
		var _g1 = 0;
		var _g = this.layoutGroups.length;
		while(_g1 < _g) {
			var i = _g1++;
			group = this.layoutGroups[i];
			if(group.lineIndex != lineIndex) {
				lineIndex = group.lineIndex;
				var _g2 = group.format.align;
				switch(_g2[1]) {
				case 3:
					if(this.lineWidths[lineIndex] < this.width - 4) offsetX = Math.round((this.width - 4 - this.lineWidths[lineIndex]) / 2); else offsetX = 0;
					break;
				case 1:
					if(this.lineWidths[lineIndex] < this.width - 4) offsetX = Math.round(this.width - 4 - this.lineWidths[lineIndex]); else offsetX = 0;
					break;
				case 2:
					if(this.lineWidths[lineIndex] < this.width - 4) {
						lineLength = 1;
						var _g4 = i + 1;
						var _g3 = this.layoutGroups.length;
						while(_g4 < _g3) {
							var j = _g4++;
							if(this.layoutGroups[j].lineIndex == lineIndex) lineLength++; else break;
						}
						if(lineLength > 1) {
							group = this.layoutGroups[i + lineLength - 1];
							if(group.endIndex < this.text.length && this.text.charAt(group.endIndex) != "\n") {
								offsetX = (this.width - 4 - this.lineWidths[lineIndex]) / (lineLength - 1);
								var _g31 = 1;
								while(_g31 < lineLength) {
									var j1 = _g31++;
									this.layoutGroups[i + j1].offsetX += offsetX * j1;
								}
							}
						}
					}
					offsetX = 0;
					break;
				default:
					offsetX = 0;
				}
			}
			if(offsetX > 0) group.offsetX += offsetX;
		}
	}
	,update: function() {
		if(this.text == null || StringTools.trim(this.text) == "" || this.textFormatRanges.length == 0) {
			this.lineAscents.splice(0,this.lineAscents.length);
			this.lineBreaks.splice(0,this.lineBreaks.length);
			this.lineDescents.splice(0,this.lineDescents.length);
			this.lineLeadings.splice(0,this.lineLeadings.length);
			this.lineHeights.splice(0,this.lineHeights.length);
			this.lineWidths.splice(0,this.lineWidths.length);
			this.layoutGroups.splice(0,this.layoutGroups.length);
			this.textWidth = 0;
			this.textHeight = 0;
			this.numLines = 1;
			this.maxScrollH = 0;
			this.maxScrollV = 1;
			this.bottomScrollV = 1;
		} else {
			this.getLayoutGroups();
			this.getLineMeasurements();
			this.setTextAlignment();
		}
		this.getBounds();
	}
	,__class__: openfl__$internal_text_TextEngine
};
var openfl__$internal_text_TextFormatRange = function(format,start,end) {
	this.format = format;
	this.start = start;
	this.end = end;
};
$hxClasses["openfl._internal.text.TextFormatRange"] = openfl__$internal_text_TextFormatRange;
openfl__$internal_text_TextFormatRange.__name__ = true;
openfl__$internal_text_TextFormatRange.prototype = {
	__class__: openfl__$internal_text_TextFormatRange
};
var openfl__$internal_text_TextLayoutGroup = function(format,startIndex,endIndex) {
	this.format = format;
	this.startIndex = startIndex;
	this.endIndex = endIndex;
};
$hxClasses["openfl._internal.text.TextLayoutGroup"] = openfl__$internal_text_TextLayoutGroup;
openfl__$internal_text_TextLayoutGroup.__name__ = true;
openfl__$internal_text_TextLayoutGroup.prototype = {
	__class__: openfl__$internal_text_TextLayoutGroup
};
var openfl_display_Application = function() {
	lime_app_Application.call(this);
	if(openfl_Lib.application == null) openfl_Lib.application = this;
};
$hxClasses["openfl.display.Application"] = openfl_display_Application;
openfl_display_Application.__name__ = true;
openfl_display_Application.__super__ = lime_app_Application;
openfl_display_Application.prototype = $extend(lime_app_Application.prototype,{
	create: function(config) {
		this.config = config;
		this.backend.create(config);
		if(config != null) {
			if(Object.prototype.hasOwnProperty.call(config,"fps")) this.backend.setFrameRate(config.fps);
			if(Object.prototype.hasOwnProperty.call(config,"windows")) {
				var _g = 0;
				var _g1 = config.windows;
				while(_g < _g1.length) {
					var windowConfig = _g1[_g];
					++_g;
					var $window = new openfl_display_Window(windowConfig);
					this.createWindow($window);
					break;
				}
			}
			if(this.preloader == null || this.preloader.complete) this.onPreloadComplete();
		}
	}
	,__class__: openfl_display_Application
});
var openfl_display_Bitmap = function(bitmapData,pixelSnapping,smoothing) {
	if(smoothing == null) smoothing = false;
	openfl_display_DisplayObject.call(this);
	this.bitmapData = bitmapData;
	this.pixelSnapping = pixelSnapping;
	this.smoothing = smoothing;
	if(pixelSnapping == null) this.pixelSnapping = openfl_display_PixelSnapping.AUTO;
};
$hxClasses["openfl.display.Bitmap"] = openfl_display_Bitmap;
openfl_display_Bitmap.__name__ = true;
openfl_display_Bitmap.__super__ = openfl_display_DisplayObject;
openfl_display_Bitmap.prototype = $extend(openfl_display_DisplayObject.prototype,{
	__getBounds: function(rect,matrix) {
		if(this.bitmapData != null) {
			var bounds = openfl_geom_Rectangle.__temp;
			bounds.setTo(0,0,this.bitmapData.width,this.bitmapData.height);
			bounds.__transform(bounds,matrix);
			rect.__expand(bounds.x,bounds.y,bounds.width,bounds.height);
		}
	}
	,__hitTest: function(x,y,shapeFlag,stack,interactiveOnly) {
		if(!this.get_visible() || this.__isMask || this.bitmapData == null) return false;
		if(this.get_mask() != null && !this.get_mask().__hitTestMask(x,y)) return false;
		this.__getWorldTransform();
		var px = this.__worldTransform.__transformInverseX(x,y);
		var py = this.__worldTransform.__transformInverseY(x,y);
		if(px > 0 && py > 0 && px <= this.bitmapData.width && py <= this.bitmapData.height) {
			if(stack != null && !interactiveOnly) stack.push(this);
			return true;
		}
		return false;
	}
	,__hitTestMask: function(x,y) {
		if(this.bitmapData == null) return false;
		this.__getWorldTransform();
		var px = this.__worldTransform.__transformInverseX(x,y);
		var py = this.__worldTransform.__transformInverseY(x,y);
		if(px > 0 && py > 0 && px <= this.bitmapData.width && py <= this.bitmapData.height) return true;
		return false;
	}
	,__renderCairo: function(renderSession) {
		openfl__$internal_renderer_cairo_CairoBitmap.render(this,renderSession);
	}
	,__renderCairoMask: function(renderSession) {
		renderSession.cairo.rectangle(0,0,this.get_width(),this.get_height());
	}
	,__renderCanvas: function(renderSession) {
		openfl__$internal_renderer_canvas_CanvasBitmap.render(this,renderSession);
	}
	,__renderCanvasMask: function(renderSession) {
		renderSession.context.rect(0,0,this.get_width(),this.get_height());
	}
	,__renderDOM: function(renderSession) {
		if(this.stage != null && this.__worldVisible && this.__renderable && this.bitmapData != null && this.bitmapData.__isValid) {
			if(this.bitmapData.image.buffer.__srcImage != null) openfl__$internal_renderer_dom_DOMBitmap.renderImage(this,renderSession); else openfl__$internal_renderer_dom_DOMBitmap.renderCanvas(this,renderSession);
		} else {
			if(this.__image != null) {
				renderSession.element.removeChild(this.__image);
				this.__image = null;
				this.__style = null;
			}
			if(this.__canvas != null) {
				renderSession.element.removeChild(this.__canvas);
				this.__canvas = null;
				this.__style = null;
			}
		}
	}
	,__renderGL: function(renderSession) {
		if(this.__cacheAsBitmap) {
			this.__cacheGL(renderSession);
			return;
		}
		if(this.__scrollRect != null) renderSession.maskManager.pushRect(this.__scrollRect,this.__renderTransform);
		if(this.__mask != null && this.__maskGraphics != null && this.__maskGraphics.__commands.get_length() > 0) renderSession.maskManager.pushMask(this);
		if(!this.__renderable || this.__worldAlpha <= 0 || this.bitmapData == null || !this.bitmapData.__isValid) null; else renderSession.spriteBatch.renderBitmapData(this.bitmapData,this.smoothing,this.__renderTransform,this.__worldColorTransform,this.__worldAlpha,this.__blendMode,this.__shader,this.pixelSnapping);
		if(this.__mask != null && this.__maskGraphics != null && this.__maskGraphics.__commands.get_length() > 0) renderSession.maskManager.popMask();
		if(this.__scrollRect != null) renderSession.maskManager.popRect();
	}
	,__updateMask: function(maskGraphics) {
		maskGraphics.__commands.overrideMatrix(this.__worldTransform);
		maskGraphics.beginFill(0);
		maskGraphics.drawRect(0,0,this.bitmapData.width,this.bitmapData.height);
		if(maskGraphics.__bounds == null) maskGraphics.__bounds = new openfl_geom_Rectangle();
		this.__getBounds(maskGraphics.__bounds,openfl_geom_Matrix.__identity);
		openfl_display_DisplayObject.prototype.__updateMask.call(this,maskGraphics);
	}
	,get_height: function() {
		if(this.bitmapData != null) return this.bitmapData.height * this.get_scaleY();
		return 0;
	}
	,set_height: function(value) {
		if(this.bitmapData != null) {
			if(value != this.bitmapData.height) this.set_scaleY(value / this.bitmapData.height);
			return value;
		}
		return 0;
	}
	,get_width: function() {
		if(this.bitmapData != null) return this.bitmapData.width * this.get_scaleX();
		return 0;
	}
	,__class__: openfl_display_Bitmap
});
var openfl_display_BitmapData = function(width,height,transparent,fillColor) {
	if(fillColor == null) fillColor = -1;
	if(transparent == null) transparent = true;
	this.__usingPingPongTexture = false;
	this.transparent = transparent;
	if(width == null) width = 0; else width = width;
	if(height == null) height = 0; else height = height;
	if(width < 0) width = 0; else width = width;
	if(height < 0) height = 0; else height = height;
	this.width = width;
	this.height = height;
	this.rect = new openfl_geom_Rectangle(0,0,width,height);
	if(width > 0 && height > 0) {
		if(transparent) {
			if((fillColor & -16777216) == 0) fillColor = 0;
		} else fillColor = -16777216 | fillColor & 16777215;
		fillColor = fillColor << 8 | fillColor >> 24 & 255;
		this.image = new lime_graphics_Image(null,0,0,width,height,fillColor);
		this.image.set_transparent(transparent);
		this.__isValid = true;
	}
	this.__createUVs();
	this.__worldTransform = new openfl_geom_Matrix();
	this.__worldColorTransform = new openfl_geom_ColorTransform();
};
$hxClasses["openfl.display.BitmapData"] = openfl_display_BitmapData;
openfl_display_BitmapData.__name__ = true;
openfl_display_BitmapData.__interfaces__ = [openfl_display_IBitmapDrawable];
openfl_display_BitmapData.fromBytes = function(bytes,rawAlpha,onload) {
	var bitmapData = new openfl_display_BitmapData(0,0,true);
	bitmapData.__fromBytes(bytes,rawAlpha,onload);
	return bitmapData;
};
openfl_display_BitmapData.fromCanvas = function(canvas,transparent) {
	if(transparent == null) transparent = true;
	if(canvas == null) return null;
	var bitmapData = new openfl_display_BitmapData(0,0,transparent);
	bitmapData.__fromImage(lime_graphics_Image.fromCanvas(canvas));
	bitmapData.image.set_transparent(transparent);
	return bitmapData;
};
openfl_display_BitmapData.fromImage = function(image,transparent) {
	if(transparent == null) transparent = true;
	if(image == null || image.buffer == null) return null;
	var bitmapData = new openfl_display_BitmapData(0,0,transparent);
	bitmapData.__fromImage(image);
	bitmapData.image.set_transparent(transparent);
	return bitmapData;
};
openfl_display_BitmapData.__asRenderTexture = function(width,height) {
	if(height == null) height = 0;
	if(width == null) width = 0;
	var b = new openfl_display_BitmapData(0,0);
	b.__resize(width,height);
	return b;
};
openfl_display_BitmapData.prototype = {
	colorTransform: function(rect,colorTransform) {
		if(!this.__isValid) return;
		this.image.colorTransform(rect.__toLimeRectangle(),colorTransform.__toLimeColorMatrix());
		this.__usingPingPongTexture = false;
	}
	,draw: function(source,matrix,colorTransform,blendMode,clipRect,smoothing) {
		if(smoothing == null) smoothing = false;
		if(!this.__isValid) return;
		if(colorTransform != null) {
			var copy = new openfl_display_BitmapData(Reflect.getProperty(source,"width"),Reflect.getProperty(source,"height"),true,0);
			copy.draw(source);
			copy.colorTransform(copy.rect,colorTransform);
			source = copy;
		}
		lime_graphics_utils_ImageCanvasUtil.convertToCanvas(this.image);
		lime_graphics_utils_ImageCanvasUtil.sync(this.image,true);
		var buffer = this.image.buffer;
		var renderSession = new openfl__$internal_renderer_RenderSession();
		renderSession.context = buffer.__srcContext;
		renderSession.roundPixels = true;
		renderSession.maskManager = new openfl__$internal_renderer_canvas_CanvasMaskManager(renderSession);
		if(!smoothing) {
			buffer.__srcContext.mozImageSmoothingEnabled = false;
			buffer.__srcContext.msImageSmoothingEnabled = false;
			buffer.__srcContext.imageSmoothingEnabled = false;
		}
		if(clipRect != null) renderSession.maskManager.pushRect(clipRect,new openfl_geom_Matrix());
		source.__updateTransforms(matrix);
		source.__updateChildren(false);
		source.__renderCanvas(renderSession);
		source.__updateTransforms();
		source.__updateChildren(true);
		if(!smoothing) {
			buffer.__srcContext.mozImageSmoothingEnabled = true;
			buffer.__srcContext.msImageSmoothingEnabled = true;
			buffer.__srcContext.imageSmoothingEnabled = true;
		}
		if(clipRect != null) renderSession.maskManager.popMask();
		buffer.__srcContext.setTransform(1,0,0,1,0,0);
		buffer.__srcImageData = null;
		buffer.data = null;
	}
	,getPixels: function(rect) {
		if(!this.__isValid) return null;
		if(rect == null) rect = this.rect;
		return this.image.getPixels(rect.__toLimeRectangle(),1);
	}
	,getSurface: function() {
		if(!this.__isValid) return null;
		if(this.__surface == null) this.__surface = lime_graphics_cairo__$CairoImageSurface_CairoImageSurface_$Impl_$.fromImage(this.image);
		return this.__surface;
	}
	,getTexture: function(gl) {
		if(!this.__isValid) return null;
		if(this.__usingPingPongTexture && this.__pingPongTexture != null) return this.__pingPongTexture.get_texture();
		if(this.__texture == null) {
			this.__texture = gl.createTexture();
			gl.bindTexture(gl.TEXTURE_2D,this.__texture);
			gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_S,gl.CLAMP_TO_EDGE);
			gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_T,gl.CLAMP_TO_EDGE);
			gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MAG_FILTER,gl.NEAREST);
			gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MIN_FILTER,gl.NEAREST);
			this.image.dirty = true;
		}
		if(this.image != null && this.image.dirty) {
			var internalFormat;
			var format;
			if(this.__surface != null) lime_graphics_cairo__$CairoSurface_CairoSurface_$Impl_$.flush(this.__surface);
			if(this.image.buffer.bitsPerPixel == 1) {
				internalFormat = gl.ALPHA;
				format = gl.ALPHA;
			} else {
				internalFormat = gl.RGBA;
				format = gl.RGBA;
			}
			gl.bindTexture(gl.TEXTURE_2D,this.__texture);
			var textureImage = this.image;
			if(!textureImage.get_premultiplied() && textureImage.get_transparent() || textureImage.get_format() != 0) {
				textureImage = textureImage.clone();
				textureImage.set_format(0);
				textureImage.set_premultiplied(true);
			}
			gl.texImage2D(gl.TEXTURE_2D,0,internalFormat,this.width,this.height,0,format,gl.UNSIGNED_BYTE,textureImage.get_data());
			gl.bindTexture(gl.TEXTURE_2D,null);
			this.image.dirty = false;
		}
		return this.__texture;
	}
	,__createUVs: function(x0,y0,x1,y1,x2,y2,x3,y3) {
		if(y3 == null) y3 = 1;
		if(x3 == null) x3 = 0;
		if(y2 == null) y2 = 1;
		if(x2 == null) x2 = 1;
		if(y1 == null) y1 = 0;
		if(x1 == null) x1 = 1;
		if(y0 == null) y0 = 0;
		if(x0 == null) x0 = 0;
		if(this.__uvData == null) this.__uvData = new openfl_display_TextureUvs();
		this.__uvData.x0 = x0;
		this.__uvData.y0 = y0;
		this.__uvData.x1 = x1;
		this.__uvData.y1 = y1;
		this.__uvData.x2 = x2;
		this.__uvData.y2 = y2;
		this.__uvData.x3 = x3;
		this.__uvData.y3 = y3;
	}
	,__drawGL: function(renderSession,source,matrix,colorTransform,blendMode,clipRect,smoothing,drawSelf,clearBuffer,readPixels,powerOfTwo) {
		if(powerOfTwo == null) powerOfTwo = true;
		if(readPixels == null) readPixels = false;
		if(clearBuffer == null) clearBuffer = false;
		if(drawSelf == null) drawSelf = false;
		if(smoothing == null) smoothing = false;
		this.__pingPongTexture = openfl__$internal_renderer_opengl_GLBitmap.pushFramebuffer(renderSession,this.__pingPongTexture,this.rect,smoothing,this.transparent,clearBuffer,powerOfTwo);
		openfl__$internal_renderer_opengl_GLBitmap.drawBitmapDrawable(renderSession,drawSelf?this:null,source,matrix,colorTransform,blendMode,clipRect);
		openfl__$internal_renderer_opengl_GLBitmap.popFramebuffer(renderSession,readPixels?this.image:null);
		var uv = this.__pingPongTexture.get_renderTexture().__uvData;
		this.__createUVs(uv.x0,uv.y0,uv.x1,uv.y1,uv.x2,uv.y2,uv.x3,uv.y3);
		this.__isValid = true;
		this.__usingPingPongTexture = true;
	}
	,__fromBytes: function(bytes,rawAlpha,onload) {
		var _g = this;
		lime_graphics_Image.fromBytes(bytes,function(image) {
			_g.__fromImage(image);
			if(rawAlpha != null) {
				lime_graphics_utils_ImageCanvasUtil.convertToCanvas(image);
				lime_graphics_utils_ImageCanvasUtil.createImageData(image);
				var data = image.buffer.data;
				var _g2 = 0;
				var _g1 = rawAlpha.length;
				while(_g2 < _g1) {
					var i = _g2++;
					var val = rawAlpha.readUnsignedByte();
					data[i * 4 + 3] = val;
				}
				image.dirty = true;
			}
			if(onload != null) onload(_g);
		});
	}
	,__fromImage: function(image) {
		if(image != null && image.buffer != null) {
			this.image = image;
			this.width = image.width;
			this.height = image.height;
			this.rect = new openfl_geom_Rectangle(0,0,image.width,image.height);
			this.__isValid = true;
		}
	}
	,__renderCanvas: function(renderSession) {
		if(!this.__isValid) return;
		lime_graphics_utils_ImageCanvasUtil.sync(this.image,false);
		var context = renderSession.context;
		if(this.__worldTransform == null) this.__worldTransform = new openfl_geom_Matrix();
		context.globalAlpha = 1;
		var transform = this.__worldTransform;
		if(renderSession.roundPixels) context.setTransform(transform.a,transform.b,transform.c,transform.d,transform.tx | 0,transform.ty | 0); else context.setTransform(transform.a,transform.b,transform.c,transform.d,transform.tx,transform.ty);
		context.drawImage(this.image.get_src(),0,0);
	}
	,__renderGL: function(renderSession) {
		renderSession.spriteBatch.renderBitmapData(this,false,this.__worldTransform,this.__worldColorTransform,this.__worldColorTransform.alphaMultiplier,this.__blendMode,this.__shader);
	}
	,__updateTransforms: function(overrideTransform) {
		if(overrideTransform == null) this.__worldTransform.identity(); else this.__worldTransform = overrideTransform;
	}
	,__sync: function() {
		lime_graphics_utils_ImageCanvasUtil.sync(this.image,false);
	}
	,__updateChildren: function(transformOnly) {
	}
	,__resize: function(width,height) {
		this.width = width;
		this.height = height;
		this.rect.width = width;
		this.rect.height = height;
	}
	,__class__: openfl_display_BitmapData
};
var openfl_display_TextureUvs = function() {
	this.y3 = 0;
	this.y2 = 0;
	this.y1 = 0;
	this.y0 = 0;
	this.x3 = 0;
	this.x2 = 0;
	this.x1 = 0;
	this.x0 = 0;
};
$hxClasses["openfl.display.TextureUvs"] = openfl_display_TextureUvs;
openfl_display_TextureUvs.__name__ = true;
openfl_display_TextureUvs.prototype = {
	__class__: openfl_display_TextureUvs
};
var openfl_display_BlendMode = $hxClasses["openfl.display.BlendMode"] = { __ename__ : true, __constructs__ : ["ADD","ALPHA","DARKEN","DIFFERENCE","ERASE","HARDLIGHT","INVERT","LAYER","LIGHTEN","MULTIPLY","NORMAL","OVERLAY","SCREEN","SUBTRACT"] };
openfl_display_BlendMode.ADD = ["ADD",0];
openfl_display_BlendMode.ADD.toString = $estr;
openfl_display_BlendMode.ADD.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.ALPHA = ["ALPHA",1];
openfl_display_BlendMode.ALPHA.toString = $estr;
openfl_display_BlendMode.ALPHA.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.DARKEN = ["DARKEN",2];
openfl_display_BlendMode.DARKEN.toString = $estr;
openfl_display_BlendMode.DARKEN.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.DIFFERENCE = ["DIFFERENCE",3];
openfl_display_BlendMode.DIFFERENCE.toString = $estr;
openfl_display_BlendMode.DIFFERENCE.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.ERASE = ["ERASE",4];
openfl_display_BlendMode.ERASE.toString = $estr;
openfl_display_BlendMode.ERASE.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.HARDLIGHT = ["HARDLIGHT",5];
openfl_display_BlendMode.HARDLIGHT.toString = $estr;
openfl_display_BlendMode.HARDLIGHT.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.INVERT = ["INVERT",6];
openfl_display_BlendMode.INVERT.toString = $estr;
openfl_display_BlendMode.INVERT.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.LAYER = ["LAYER",7];
openfl_display_BlendMode.LAYER.toString = $estr;
openfl_display_BlendMode.LAYER.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.LIGHTEN = ["LIGHTEN",8];
openfl_display_BlendMode.LIGHTEN.toString = $estr;
openfl_display_BlendMode.LIGHTEN.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.MULTIPLY = ["MULTIPLY",9];
openfl_display_BlendMode.MULTIPLY.toString = $estr;
openfl_display_BlendMode.MULTIPLY.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.NORMAL = ["NORMAL",10];
openfl_display_BlendMode.NORMAL.toString = $estr;
openfl_display_BlendMode.NORMAL.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.OVERLAY = ["OVERLAY",11];
openfl_display_BlendMode.OVERLAY.toString = $estr;
openfl_display_BlendMode.OVERLAY.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.SCREEN = ["SCREEN",12];
openfl_display_BlendMode.SCREEN.toString = $estr;
openfl_display_BlendMode.SCREEN.__enum__ = openfl_display_BlendMode;
openfl_display_BlendMode.SUBTRACT = ["SUBTRACT",13];
openfl_display_BlendMode.SUBTRACT.toString = $estr;
openfl_display_BlendMode.SUBTRACT.__enum__ = openfl_display_BlendMode;
var openfl_display_CapsStyle = $hxClasses["openfl.display.CapsStyle"] = { __ename__ : true, __constructs__ : ["NONE","ROUND","SQUARE"] };
openfl_display_CapsStyle.NONE = ["NONE",0];
openfl_display_CapsStyle.NONE.toString = $estr;
openfl_display_CapsStyle.NONE.__enum__ = openfl_display_CapsStyle;
openfl_display_CapsStyle.ROUND = ["ROUND",1];
openfl_display_CapsStyle.ROUND.toString = $estr;
openfl_display_CapsStyle.ROUND.__enum__ = openfl_display_CapsStyle;
openfl_display_CapsStyle.SQUARE = ["SQUARE",2];
openfl_display_CapsStyle.SQUARE.toString = $estr;
openfl_display_CapsStyle.SQUARE.__enum__ = openfl_display_CapsStyle;
var openfl_display_FrameLabel = function() { };
$hxClasses["openfl.display.FrameLabel"] = openfl_display_FrameLabel;
openfl_display_FrameLabel.__name__ = true;
openfl_display_FrameLabel.__super__ = openfl_events_EventDispatcher;
openfl_display_FrameLabel.prototype = $extend(openfl_events_EventDispatcher.prototype,{
	__class__: openfl_display_FrameLabel
});
var openfl_display_GradientType = $hxClasses["openfl.display.GradientType"] = { __ename__ : true, __constructs__ : ["RADIAL","LINEAR"] };
openfl_display_GradientType.RADIAL = ["RADIAL",0];
openfl_display_GradientType.RADIAL.toString = $estr;
openfl_display_GradientType.RADIAL.__enum__ = openfl_display_GradientType;
openfl_display_GradientType.LINEAR = ["LINEAR",1];
openfl_display_GradientType.LINEAR.toString = $estr;
openfl_display_GradientType.LINEAR.__enum__ = openfl_display_GradientType;
var openfl_display_Graphics = function() {
	this.__glStack = [];
	this.__dirty = true;
	this.__commands = new openfl__$internal_renderer_DrawCommandBuffer();
	this.__strokePadding = 0;
	this.__positionX = 0;
	this.__positionY = 0;
	this.__hardware = true;
	this.moveTo(0,0);
};
$hxClasses["openfl.display.Graphics"] = openfl_display_Graphics;
openfl_display_Graphics.__name__ = true;
openfl_display_Graphics.prototype = {
	beginBitmapFill: function(bitmap,matrix,repeat,smooth) {
		if(smooth == null) smooth = false;
		if(repeat == null) repeat = true;
		this.__commands.beginBitmapFill(bitmap,matrix != null?new openfl_geom_Matrix(matrix.a,matrix.b,matrix.c,matrix.d,matrix.tx,matrix.ty):null,repeat,smooth);
		this.__visible = true;
	}
	,beginFill: function(color,alpha) {
		if(alpha == null) alpha = 1;
		if(color == null) color = 0;
		this.__commands.beginFill(color & 16777215,alpha);
		if(alpha > 0) this.__visible = true;
	}
	,clear: function() {
		this.__commands.clear();
		this.__strokePadding = 0;
		if(this.__bounds != null) {
			this.set___dirty(true);
			this.__transformDirty = true;
			this.__bounds = null;
		}
		this.__visible = false;
		this.__hardware = true;
		this.moveTo(0,0);
	}
	,drawRect: function(x,y,width,height) {
		if(width <= 0 || height <= 0) return;
		this.__inflateBounds(x - this.__strokePadding,y - this.__strokePadding);
		this.__inflateBounds(x + width + this.__strokePadding,y + height + this.__strokePadding);
		this.__commands.drawRect(x,y,width,height);
		this.set___dirty(true);
	}
	,endFill: function() {
		this.__commands.endFill();
	}
	,lineStyle: function(thickness,color,alpha,pixelHinting,scaleMode,caps,joints,miterLimit) {
		if(thickness != null) {
			if(joints == openfl_display_JointStyle.MITER) {
				if(thickness > this.__strokePadding) this.__strokePadding = thickness;
			} else if(thickness / 2 > this.__strokePadding) this.__strokePadding = thickness / 2;
		}
		this.__commands.lineStyle(thickness,color,alpha,pixelHinting,scaleMode,caps,joints,miterLimit);
		if(thickness != null) this.__visible = true;
	}
	,lineTo: function(x,y) {
		this.__inflateBounds(this.__positionX - this.__strokePadding,this.__positionY - this.__strokePadding);
		this.__inflateBounds(this.__positionX + this.__strokePadding,this.__positionY + this.__strokePadding);
		this.__positionX = x;
		this.__positionY = y;
		this.__inflateBounds(this.__positionX - this.__strokePadding,this.__positionY - this.__strokePadding);
		this.__inflateBounds(this.__positionX + this.__strokePadding * 2,this.__positionY + this.__strokePadding);
		this.__commands.lineTo(x,y);
		this.__hardware = false;
		this.set___dirty(true);
	}
	,moveTo: function(x,y) {
		this.__positionX = x;
		this.__positionY = y;
		this.__commands.moveTo(x,y);
	}
	,__getBounds: function(rect,matrix) {
		if(this.__bounds == null) return;
		var bounds = openfl_geom_Rectangle.__temp;
		this.__bounds.__transform(bounds,matrix);
		rect.__expand(bounds.x,bounds.y,bounds.width,bounds.height);
	}
	,__hitTest: function(x,y,shapeFlag,matrix) {
		if(this.__bounds == null) return false;
		var px = matrix.__transformInverseX(x,y);
		var py = matrix.__transformInverseY(x,y);
		if(px > this.__bounds.x && py > this.__bounds.y && this.__bounds.contains(px,py)) {
			if(shapeFlag) return openfl__$internal_renderer_canvas_CanvasGraphics.hitTest(this,px,py);
			return true;
		}
		return false;
	}
	,__inflateBounds: function(x,y) {
		if(this.__bounds == null) {
			this.__bounds = new openfl_geom_Rectangle(x,y,0,0);
			this.__transformDirty = true;
			return;
		}
		if(x < this.__bounds.x) {
			this.__bounds.width += this.__bounds.x - x;
			this.__bounds.x = x;
			this.__transformDirty = true;
		}
		if(y < this.__bounds.y) {
			this.__bounds.height += this.__bounds.y - y;
			this.__bounds.y = y;
			this.__transformDirty = true;
		}
		if(x > this.__bounds.x + this.__bounds.width) this.__bounds.width = x - this.__bounds.x;
		if(y > this.__bounds.y + this.__bounds.height) this.__bounds.height = y - this.__bounds.y;
	}
	,set___dirty: function(value) {
		if(value && this.__owner != null) this.__owner.__setRenderDirty();
		return this.__dirty = value;
	}
	,__class__: openfl_display_Graphics
	,__properties__: {set___dirty:"set___dirty"}
};
var openfl_display_GraphicsPathWinding = $hxClasses["openfl.display.GraphicsPathWinding"] = { __ename__ : true, __constructs__ : ["EVEN_ODD","NON_ZERO"] };
openfl_display_GraphicsPathWinding.EVEN_ODD = ["EVEN_ODD",0];
openfl_display_GraphicsPathWinding.EVEN_ODD.toString = $estr;
openfl_display_GraphicsPathWinding.EVEN_ODD.__enum__ = openfl_display_GraphicsPathWinding;
openfl_display_GraphicsPathWinding.NON_ZERO = ["NON_ZERO",1];
openfl_display_GraphicsPathWinding.NON_ZERO.toString = $estr;
openfl_display_GraphicsPathWinding.NON_ZERO.__enum__ = openfl_display_GraphicsPathWinding;
var openfl_display_InterpolationMethod = $hxClasses["openfl.display.InterpolationMethod"] = { __ename__ : true, __constructs__ : ["RGB","LINEAR_RGB"] };
openfl_display_InterpolationMethod.RGB = ["RGB",0];
openfl_display_InterpolationMethod.RGB.toString = $estr;
openfl_display_InterpolationMethod.RGB.__enum__ = openfl_display_InterpolationMethod;
openfl_display_InterpolationMethod.LINEAR_RGB = ["LINEAR_RGB",1];
openfl_display_InterpolationMethod.LINEAR_RGB.toString = $estr;
openfl_display_InterpolationMethod.LINEAR_RGB.__enum__ = openfl_display_InterpolationMethod;
var openfl_display_JointStyle = $hxClasses["openfl.display.JointStyle"] = { __ename__ : true, __constructs__ : ["MITER","ROUND","BEVEL"] };
openfl_display_JointStyle.MITER = ["MITER",0];
openfl_display_JointStyle.MITER.toString = $estr;
openfl_display_JointStyle.MITER.__enum__ = openfl_display_JointStyle;
openfl_display_JointStyle.ROUND = ["ROUND",1];
openfl_display_JointStyle.ROUND.toString = $estr;
openfl_display_JointStyle.ROUND.__enum__ = openfl_display_JointStyle;
openfl_display_JointStyle.BEVEL = ["BEVEL",2];
openfl_display_JointStyle.BEVEL.toString = $estr;
openfl_display_JointStyle.BEVEL.__enum__ = openfl_display_JointStyle;
var openfl_display_LineScaleMode = $hxClasses["openfl.display.LineScaleMode"] = { __ename__ : true, __constructs__ : ["HORIZONTAL","NONE","NORMAL","VERTICAL"] };
openfl_display_LineScaleMode.HORIZONTAL = ["HORIZONTAL",0];
openfl_display_LineScaleMode.HORIZONTAL.toString = $estr;
openfl_display_LineScaleMode.HORIZONTAL.__enum__ = openfl_display_LineScaleMode;
openfl_display_LineScaleMode.NONE = ["NONE",1];
openfl_display_LineScaleMode.NONE.toString = $estr;
openfl_display_LineScaleMode.NONE.__enum__ = openfl_display_LineScaleMode;
openfl_display_LineScaleMode.NORMAL = ["NORMAL",2];
openfl_display_LineScaleMode.NORMAL.toString = $estr;
openfl_display_LineScaleMode.NORMAL.__enum__ = openfl_display_LineScaleMode;
openfl_display_LineScaleMode.VERTICAL = ["VERTICAL",3];
openfl_display_LineScaleMode.VERTICAL.toString = $estr;
openfl_display_LineScaleMode.VERTICAL.__enum__ = openfl_display_LineScaleMode;
var openfl_display_Loader = function() { };
$hxClasses["openfl.display.Loader"] = openfl_display_Loader;
openfl_display_Loader.__name__ = true;
openfl_display_Loader.__super__ = openfl_display_Sprite;
openfl_display_Loader.prototype = $extend(openfl_display_Sprite.prototype,{
	__class__: openfl_display_Loader
});
var openfl_display_PixelSnapping = $hxClasses["openfl.display.PixelSnapping"] = { __ename__ : true, __constructs__ : ["NEVER","AUTO","ALWAYS"] };
openfl_display_PixelSnapping.NEVER = ["NEVER",0];
openfl_display_PixelSnapping.NEVER.toString = $estr;
openfl_display_PixelSnapping.NEVER.__enum__ = openfl_display_PixelSnapping;
openfl_display_PixelSnapping.AUTO = ["AUTO",1];
openfl_display_PixelSnapping.AUTO.toString = $estr;
openfl_display_PixelSnapping.AUTO.__enum__ = openfl_display_PixelSnapping;
openfl_display_PixelSnapping.ALWAYS = ["ALWAYS",2];
openfl_display_PixelSnapping.ALWAYS.toString = $estr;
openfl_display_PixelSnapping.ALWAYS.__enum__ = openfl_display_PixelSnapping;
var openfl_display_Preloader = function(display) {
	lime_app_Preloader.call(this);
	if(display != null) {
		this.display = display;
		openfl_Lib.current.addChild(display);
		if(js_Boot.__instanceof(display,NMEPreloader)) (js_Boot.__cast(display , NMEPreloader)).onInit();
	}
};
$hxClasses["openfl.display.Preloader"] = openfl_display_Preloader;
openfl_display_Preloader.__name__ = true;
openfl_display_Preloader.__super__ = lime_app_Preloader;
openfl_display_Preloader.prototype = $extend(lime_app_Preloader.prototype,{
	load: function(urls,types) {
		var sounds = [];
		var url = null;
		var _g1 = 0;
		var _g = urls.length;
		while(_g1 < _g) {
			var i = _g1++;
			url = urls[i];
			var _g2 = types[i];
			switch(_g2) {
			case "MUSIC":case "SOUND":
				var sound = haxe_io_Path.withoutExtension(url);
				if(!HxOverrides.remove(sounds,sound)) this.total++;
				sounds.push(sound);
				break;
			default:
			}
		}
		var _g3 = 0;
		while(_g3 < sounds.length) {
			var soundName = sounds[_g3];
			++_g3;
			var sound1 = new openfl_media_Sound();
			sound1.addEventListener(openfl_events_Event.COMPLETE,$bind(this,this.sound_onComplete));
			sound1.addEventListener(openfl_events_IOErrorEvent.IO_ERROR,$bind(this,this.sound_onIOError));
			sound1.load(new openfl_net_URLRequest(soundName + ".ogg"));
		}
		lime_app_Preloader.prototype.load.call(this,urls,types);
	}
	,start: function() {
		if(this.display != null && js_Boot.__instanceof(this.display,NMEPreloader)) {
			this.display.addEventListener(openfl_events_Event.COMPLETE,$bind(this,this.display_onComplete));
			(js_Boot.__cast(this.display , NMEPreloader)).onLoaded();
		} else lime_app_Preloader.prototype.start.call(this);
	}
	,update: function(loaded,total) {
		if(this.display != null && js_Boot.__instanceof(this.display,NMEPreloader)) (js_Boot.__cast(this.display , NMEPreloader)).onUpdate(loaded,total);
	}
	,display_onComplete: function(event) {
		this.display.removeEventListener(openfl_events_Event.COMPLETE,$bind(this,this.display_onComplete));
		openfl_Lib.current.removeChild(this.display);
		openfl_Lib.current.stage.set_focus(null);
		this.display = null;
		lime_app_Preloader.prototype.start.call(this);
	}
	,sound_onComplete: function(event) {
		this.loaded++;
		this.onProgress.dispatch(this.loaded,this.total);
		if(this.loaded == this.total) this.start();
	}
	,sound_onIOError: function(event) {
		this.loaded++;
		this.onProgress.dispatch(this.loaded,this.total);
		if(this.loaded == this.total) this.start();
	}
	,__class__: openfl_display_Preloader
});
var openfl_display_Shader = function() {
	this.__dirty = true;
	this.repeatY = 33071;
	this.repeatX = 33071;
};
$hxClasses["openfl.display.Shader"] = openfl_display_Shader;
openfl_display_Shader.__name__ = true;
openfl_display_Shader.prototype = {
	__init: function(gl) {
		var dirty = this.__dirty;
		if(dirty) {
			if(this.__shader != null) this.__shader.destroy();
			this.__shader = new openfl__$internal_renderer_opengl_shaders2_Shader(gl);
			if(this.__vertexCode != null) this.__shader.vertexString = this.__vertexCode; else this.__shader.vertexString = openfl__$internal_renderer_opengl_shaders2_DefaultShader.VERTEX_SRC.join("\n");
			this.__shader.fragmentString = this.__fragmentCode;
			this.__dirty = false;
		}
		this.__shader.init(dirty);
	}
	,__class__: openfl_display_Shader
};
var openfl_display_GLShaderParameter = function() {
	this.internalType = 0;
	this.transpose = false;
	this.repeatY = 33071;
	this.repeatX = 33071;
	this.smooth = false;
	this.size = 0;
};
$hxClasses["openfl.display.GLShaderParameter"] = openfl_display_GLShaderParameter;
openfl_display_GLShaderParameter.__name__ = true;
openfl_display_GLShaderParameter.prototype = {
	__class__: openfl_display_GLShaderParameter
};
var openfl_display_SpreadMethod = $hxClasses["openfl.display.SpreadMethod"] = { __ename__ : true, __constructs__ : ["REPEAT","REFLECT","PAD"] };
openfl_display_SpreadMethod.REPEAT = ["REPEAT",0];
openfl_display_SpreadMethod.REPEAT.toString = $estr;
openfl_display_SpreadMethod.REPEAT.__enum__ = openfl_display_SpreadMethod;
openfl_display_SpreadMethod.REFLECT = ["REFLECT",1];
openfl_display_SpreadMethod.REFLECT.toString = $estr;
openfl_display_SpreadMethod.REFLECT.__enum__ = openfl_display_SpreadMethod;
openfl_display_SpreadMethod.PAD = ["PAD",2];
openfl_display_SpreadMethod.PAD.toString = $estr;
openfl_display_SpreadMethod.PAD.__enum__ = openfl_display_SpreadMethod;
var openfl_display_Stage = function(window,color) {
	openfl_display_DisplayObjectContainer.call(this);
	this.application = window.application;
	this.window = window;
	if(color == null) {
		this.__transparent = true;
		this.set_color(0);
	} else this.set_color(color);
	this.set_name(null);
	this.__deltaTime = 0;
	this.__displayState = openfl_display_StageDisplayState.NORMAL;
	this.__mouseX = 0;
	this.__mouseY = 0;
	this.__lastClickTime = 0;
	this.stageWidth = window.__width;
	this.stageHeight = window.__height;
	this.stage = this;
	this.align = openfl_display_StageAlign.TOP_LEFT;
	this.allowsFullScreen = false;
	this.allowsFullScreenInteractive = false;
	this.quality = openfl_display_StageQuality.HIGH;
	this.scaleMode = openfl_display_StageScaleMode.NO_SCALE;
	this.stageFocusRect = true;
	this.__macKeyboard = /AppleWebKit/.test (navigator.userAgent) && /Mobile\/\w+/.test (navigator.userAgent) || /Mac/.test (navigator.platform);
	this.__clearBeforeRender = true;
	this.__stack = [];
	this.__mouseOutStack = [];
	var this1;
	this1 = new openfl_VectorData();
	var this2;
	this2 = new Array(0);
	this1.data = this2;
	this1.length = 0;
	this1.fixed = false;
	this.stage3Ds = this1;
	var this3 = this.stage3Ds;
	var x = new openfl_display_Stage3D();
	if(!this3.fixed) {
		this3.length++;
		if(this3.data.length < this3.length) {
			var data;
			var this4;
			this4 = new Array(this3.data.length + 10);
			data = this4;
			haxe_ds__$Vector_Vector_$Impl_$.blit(this3.data,0,data,0,this3.data.length);
			this3.data = data;
		}
		this3.data[this3.length - 1] = x;
	}
	this3.length;
	if(openfl_Lib.current.stage == null) this.stage.addChild(openfl_Lib.current);
};
$hxClasses["openfl.display.Stage"] = openfl_display_Stage;
openfl_display_Stage.__name__ = true;
openfl_display_Stage.__interfaces__ = [lime_app_IModule];
openfl_display_Stage.__super__ = openfl_display_DisplayObjectContainer;
openfl_display_Stage.prototype = $extend(openfl_display_DisplayObjectContainer.prototype,{
	globalToLocal: function(pos) {
		return pos.clone();
	}
	,localToGlobal: function(pos) {
		return pos.clone();
	}
	,onGamepadAxisMove: function(gamepad,axis,value) {
		openfl_ui_GameInput.__onGamepadAxisMove(gamepad,axis,value);
	}
	,onGamepadButtonDown: function(gamepad,button) {
		openfl_ui_GameInput.__onGamepadButtonDown(gamepad,button);
	}
	,onGamepadButtonUp: function(gamepad,button) {
		openfl_ui_GameInput.__onGamepadButtonUp(gamepad,button);
	}
	,onGamepadConnect: function(gamepad) {
		openfl_ui_GameInput.__onGamepadConnect(gamepad);
	}
	,onGamepadDisconnect: function(gamepad) {
		openfl_ui_GameInput.__onGamepadDisconnect(gamepad);
	}
	,onJoystickAxisMove: function(joystick,axis,value) {
	}
	,onJoystickButtonDown: function(joystick,button) {
	}
	,onJoystickButtonUp: function(joystick,button) {
	}
	,onJoystickConnect: function(joystick) {
	}
	,onJoystickDisconnect: function(joystick) {
	}
	,onJoystickHatMove: function(joystick,hat,position) {
	}
	,onJoystickTrackballMove: function(joystick,trackball,value) {
	}
	,onKeyDown: function(window,keyCode,modifier) {
		if(this.window == null || this.window != window) return;
		this.__onKey(openfl_events_KeyboardEvent.KEY_DOWN,keyCode,modifier);
	}
	,onKeyUp: function(window,keyCode,modifier) {
		if(this.window == null || this.window != window) return;
		this.__onKey(openfl_events_KeyboardEvent.KEY_UP,keyCode,modifier);
	}
	,onModuleExit: function(code) {
		if(this.window != null) {
			var event = new openfl_events_Event(openfl_events_Event.DEACTIVATE);
			this.__broadcast(event,true);
		}
	}
	,onMouseDown: function(window,x,y,button) {
		if(this.window == null || this.window != window) return;
		var type;
		switch(button) {
		case 1:
			type = openfl_events_MouseEvent.MIDDLE_MOUSE_DOWN;
			break;
		case 2:
			type = openfl_events_MouseEvent.RIGHT_MOUSE_DOWN;
			break;
		default:
			type = openfl_events_MouseEvent.MOUSE_DOWN;
		}
		this.__onMouse(type,x,y,button);
	}
	,onMouseMove: function(window,x,y) {
		if(this.window == null || this.window != window) return;
		this.__onMouse(openfl_events_MouseEvent.MOUSE_MOVE,x,y,0);
	}
	,onMouseMoveRelative: function(window,x,y) {
	}
	,onMouseUp: function(window,x,y,button) {
		if(this.window == null || this.window != window) return;
		var type;
		switch(button) {
		case 1:
			type = openfl_events_MouseEvent.MIDDLE_MOUSE_UP;
			break;
		case 2:
			type = openfl_events_MouseEvent.RIGHT_MOUSE_UP;
			break;
		default:
			type = openfl_events_MouseEvent.MOUSE_UP;
		}
		this.__onMouse(type,x,y,button);
	}
	,onMouseWheel: function(window,deltaX,deltaY) {
		if(this.window == null || this.window != window) return;
		this.__onMouseWheel(deltaX,deltaY);
	}
	,onPreloadComplete: function() {
	}
	,onPreloadProgress: function(loaded,total) {
	}
	,onRenderContextLost: function(renderer) {
	}
	,onRenderContextRestored: function(renderer,context) {
	}
	,onTextEdit: function(window,text,start,length) {
	}
	,onTextInput: function(window,text) {
		if(this.window == null || this.window != window) return;
		var stack = [];
		if(this.__focus == null) this.__getInteractive(stack); else this.__focus.__getInteractive(stack);
		var event = new openfl_events_TextEvent(openfl_events_TextEvent.TEXT_INPUT,true,false,text);
		if(stack.length > 0) {
			stack.reverse();
			this.__fireEvent(event,stack);
		} else this.__broadcast(event,true);
	}
	,onTouchMove: function(touch) {
		this.__onTouch("touchMove",touch);
	}
	,onTouchEnd: function(touch) {
		this.__onTouch("touchEnd",touch);
	}
	,onTouchStart: function(touch) {
		this.__onTouch("touchBegin",touch);
	}
	,onWindowActivate: function(window) {
		if(this.window == null || this.window != window) return;
		var event = new openfl_events_Event(openfl_events_Event.ACTIVATE);
		this.__broadcast(event,true);
	}
	,onWindowClose: function(window) {
		if(this.window == window) this.window = null;
	}
	,onWindowCreate: function(window) {
		if(this.window == null || this.window != window) return;
		if(window.renderer != null) {
			var _g = window.renderer.context;
			switch(_g[1]) {
			case 0:
				var gl = _g[2];
				this.__renderer = new openfl__$internal_renderer_opengl_GLRenderer(this.stageWidth,this.stageHeight,gl);
				break;
			case 1:
				var context = _g[2];
				this.__renderer = new openfl__$internal_renderer_canvas_CanvasRenderer(this.stageWidth,this.stageHeight,context);
				break;
			case 2:
				var element = _g[2];
				this.__renderer = new openfl__$internal_renderer_dom_DOMRenderer(this.stageWidth,this.stageHeight,element);
				break;
			case 4:
				var cairo = _g[2];
				this.__renderer = new openfl__$internal_renderer_cairo_CairoRenderer(this.stageWidth,this.stageHeight,cairo);
				break;
			case 5:
				var ctx = _g[2];
				this.__renderer = new openfl__$internal_renderer_console_ConsoleRenderer(this.stageWidth,this.stageHeight,ctx);
				break;
			default:
			}
		}
	}
	,onWindowDeactivate: function(window) {
		if(this.window == null || this.window != window) return;
		var event = new openfl_events_Event(openfl_events_Event.DEACTIVATE);
		this.__broadcast(event,true);
	}
	,onWindowEnter: function(window) {
	}
	,onWindowFocusIn: function(window) {
		if(this.window == null || this.window != window) return;
		var event = new openfl_events_FocusEvent(openfl_events_FocusEvent.FOCUS_IN,true,false,null,false,0);
		this.__broadcast(event,true);
	}
	,onWindowFocusOut: function(window) {
		if(this.window == null || this.window != window) return;
		var event = new openfl_events_FocusEvent(openfl_events_FocusEvent.FOCUS_OUT,true,false,null,false,0);
		this.__broadcast(event,true);
	}
	,onWindowFullscreen: function(window) {
	}
	,onWindowLeave: function(window) {
		if(this.window == null || this.window != window) return;
		this.__dispatchEvent(new openfl_events_Event(openfl_events_Event.MOUSE_LEAVE));
	}
	,onWindowMinimize: function(window) {
	}
	,onWindowMove: function(window,x,y) {
	}
	,onWindowResize: function(window,width,height) {
		if(this.window == null || this.window != window) return;
		this.stageWidth = width;
		this.stageHeight = height;
		if(this.__renderer != null) this.__renderer.resize(width,height);
		var event = new openfl_events_Event(openfl_events_Event.RESIZE);
		this.__broadcast(event,false);
	}
	,onWindowRestore: function(window) {
	}
	,render: function(renderer) {
		if(renderer.window == null || renderer.window != this.window) return;
		if(this.application != null && this.application.windows.length > 0) {
			if(!this.__transformDirty) {
				this.__transformDirty = true;
				openfl_display_DisplayObject.__worldTransformDirty++;
			}
			if(!this.__renderDirty) {
				this.__updateCachedBitmap = true;
				this.__updateFilters = this.get_filters() != null && this.get_filters().length > 0;
				this.__renderDirty = true;
				openfl_display_DisplayObject.__worldRenderDirty++;
			}
		}
		if(this.__rendering) return;
		this.__rendering = true;
		this.__broadcast(new openfl_events_Event(openfl_events_Event.ENTER_FRAME),true);
		if(this.__invalidated) {
			this.__invalidated = false;
			this.__broadcast(new openfl_events_Event(openfl_events_Event.RENDER),true);
		}
		this.__renderable = true;
		this.__enterFrame(this.__deltaTime);
		this.__deltaTime = 0;
		this.__update(false,true);
		if(this.__renderer != null) {
			{
				var _g = renderer.context;
				switch(_g[1]) {
				case 4:
					var cairo = _g[2];
					(js_Boot.__cast(this.__renderer , openfl__$internal_renderer_cairo_CairoRenderer)).cairo = cairo;
					this.__renderer.renderSession.cairo = cairo;
					break;
				default:
				}
			}
			this.__renderer.render(this);
		}
		this.__rendering = false;
	}
	,update: function(deltaTime) {
		this.__deltaTime = deltaTime;
	}
	,__drag: function(mouse) {
		var parent = this.__dragObject.parent;
		if(parent != null) mouse = parent.globalToLocal(mouse);
		var x = mouse.x + this.__dragOffsetX;
		var y = mouse.y + this.__dragOffsetY;
		if(this.__dragBounds != null) {
			if(x < this.__dragBounds.x) x = this.__dragBounds.x; else if(x > this.__dragBounds.get_right()) x = this.__dragBounds.get_right();
			if(y < this.__dragBounds.y) y = this.__dragBounds.y; else if(y > this.__dragBounds.get_bottom()) y = this.__dragBounds.get_bottom();
		}
		this.__dragObject.set_x(x);
		this.__dragObject.set_y(y);
	}
	,__fireEvent: function(event,stack) {
		var length = stack.length;
		if(length == 0) {
			event.eventPhase = openfl_events_EventPhase.AT_TARGET;
			event.target.__broadcast(event,false);
		} else {
			event.eventPhase = openfl_events_EventPhase.CAPTURING_PHASE;
			event.target = stack[stack.length - 1];
			var _g1 = 0;
			var _g = length - 1;
			while(_g1 < _g) {
				var i = _g1++;
				stack[i].__broadcast(event,false);
				if(event.__isCancelled) return;
			}
			event.eventPhase = openfl_events_EventPhase.AT_TARGET;
			event.target.__broadcast(event,false);
			if(event.__isCancelled) return;
			if(event.bubbles) {
				event.eventPhase = openfl_events_EventPhase.BUBBLING_PHASE;
				var i1 = length - 2;
				while(i1 >= 0) {
					stack[i1].__broadcast(event,false);
					if(event.__isCancelled) return;
					i1--;
				}
			}
		}
	}
	,__getInteractive: function(stack) {
		if(stack != null) stack.push(this);
		return true;
	}
	,__onKey: function(type,keyCode,modifier) {
		openfl_events_MouseEvent.__altKey = lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_altKey(modifier);
		openfl_events_MouseEvent.__commandKey = lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_metaKey(modifier);
		openfl_events_MouseEvent.__ctrlKey = lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_ctrlKey(modifier);
		openfl_events_MouseEvent.__shiftKey = lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_shiftKey(modifier);
		var stack = [];
		if(this.__focus == null) this.__getInteractive(stack); else this.__focus.__getInteractive(stack);
		if(stack.length > 0) {
			var keyLocation;
			switch(keyCode) {
			case 1073742048:case 1073742049:case 1073742050:case 1073742051:
				keyLocation = 1;
				break;
			case 1073742052:case 1073742053:case 1073742054:case 1073742055:
				keyLocation = 2;
				break;
			case 1073741908:case 1073741909:case 1073741910:case 1073741911:case 1073741912:case 1073741913:case 1073741914:case 1073741915:case 1073741916:case 1073741917:case 1073741918:case 1073741919:case 1073741920:case 1073741921:case 1073741922:case 1073741923:case 1073742044:
				keyLocation = 3;
				break;
			default:
				keyLocation = 0;
			}
			var keyCode1;
			switch(keyCode) {
			case 8:
				keyCode1 = 8;
				break;
			case 9:
				keyCode1 = 9;
				break;
			case 13:
				keyCode1 = 13;
				break;
			case 27:
				keyCode1 = 27;
				break;
			case 32:
				keyCode1 = 32;
				break;
			case 33:
				keyCode1 = 49;
				break;
			case 34:
				keyCode1 = 222;
				break;
			case 35:
				keyCode1 = 51;
				break;
			case 36:
				keyCode1 = 52;
				break;
			case 37:
				keyCode1 = 53;
				break;
			case 38:
				keyCode1 = 55;
				break;
			case 39:
				keyCode1 = 222;
				break;
			case 40:
				keyCode1 = 57;
				break;
			case 41:
				keyCode1 = 48;
				break;
			case 42:
				keyCode1 = 56;
				break;
			case 44:
				keyCode1 = 188;
				break;
			case 45:
				keyCode1 = 189;
				break;
			case 46:
				keyCode1 = 190;
				break;
			case 47:
				keyCode1 = 191;
				break;
			case 48:
				keyCode1 = 48;
				break;
			case 49:
				keyCode1 = 49;
				break;
			case 50:
				keyCode1 = 50;
				break;
			case 51:
				keyCode1 = 51;
				break;
			case 52:
				keyCode1 = 52;
				break;
			case 53:
				keyCode1 = 53;
				break;
			case 54:
				keyCode1 = 54;
				break;
			case 55:
				keyCode1 = 55;
				break;
			case 56:
				keyCode1 = 56;
				break;
			case 57:
				keyCode1 = 57;
				break;
			case 58:
				keyCode1 = 186;
				break;
			case 59:
				keyCode1 = 186;
				break;
			case 60:
				keyCode1 = 60;
				break;
			case 61:
				keyCode1 = 187;
				break;
			case 62:
				keyCode1 = 190;
				break;
			case 63:
				keyCode1 = 191;
				break;
			case 64:
				keyCode1 = 50;
				break;
			case 91:
				keyCode1 = 219;
				break;
			case 92:
				keyCode1 = 220;
				break;
			case 93:
				keyCode1 = 221;
				break;
			case 94:
				keyCode1 = 54;
				break;
			case 95:
				keyCode1 = 189;
				break;
			case 96:
				keyCode1 = 192;
				break;
			case 97:
				keyCode1 = 65;
				break;
			case 98:
				keyCode1 = 66;
				break;
			case 99:
				keyCode1 = 67;
				break;
			case 100:
				keyCode1 = 68;
				break;
			case 101:
				keyCode1 = 69;
				break;
			case 102:
				keyCode1 = 70;
				break;
			case 103:
				keyCode1 = 71;
				break;
			case 104:
				keyCode1 = 72;
				break;
			case 105:
				keyCode1 = 73;
				break;
			case 106:
				keyCode1 = 74;
				break;
			case 107:
				keyCode1 = 75;
				break;
			case 108:
				keyCode1 = 76;
				break;
			case 109:
				keyCode1 = 77;
				break;
			case 110:
				keyCode1 = 78;
				break;
			case 111:
				keyCode1 = 79;
				break;
			case 112:
				keyCode1 = 80;
				break;
			case 113:
				keyCode1 = 81;
				break;
			case 114:
				keyCode1 = 82;
				break;
			case 115:
				keyCode1 = 83;
				break;
			case 116:
				keyCode1 = 84;
				break;
			case 117:
				keyCode1 = 85;
				break;
			case 118:
				keyCode1 = 86;
				break;
			case 119:
				keyCode1 = 87;
				break;
			case 120:
				keyCode1 = 88;
				break;
			case 121:
				keyCode1 = 89;
				break;
			case 122:
				keyCode1 = 90;
				break;
			case 127:
				keyCode1 = 46;
				break;
			case 1073741881:
				keyCode1 = 20;
				break;
			case 1073741882:
				keyCode1 = 112;
				break;
			case 1073741883:
				keyCode1 = 113;
				break;
			case 1073741884:
				keyCode1 = 114;
				break;
			case 1073741885:
				keyCode1 = 115;
				break;
			case 1073741886:
				keyCode1 = 116;
				break;
			case 1073741887:
				keyCode1 = 117;
				break;
			case 1073741888:
				keyCode1 = 118;
				break;
			case 1073741889:
				keyCode1 = 119;
				break;
			case 1073741890:
				keyCode1 = 120;
				break;
			case 1073741891:
				keyCode1 = 121;
				break;
			case 1073741892:
				keyCode1 = 122;
				break;
			case 1073741893:
				keyCode1 = 123;
				break;
			case 1073741894:
				keyCode1 = 301;
				break;
			case 1073741895:
				keyCode1 = 145;
				break;
			case 1073741896:
				keyCode1 = 19;
				break;
			case 1073741897:
				keyCode1 = 45;
				break;
			case 1073741898:
				keyCode1 = 36;
				break;
			case 1073741899:
				keyCode1 = 33;
				break;
			case 1073741901:
				keyCode1 = 35;
				break;
			case 1073741902:
				keyCode1 = 34;
				break;
			case 1073741903:
				keyCode1 = 39;
				break;
			case 1073741904:
				keyCode1 = 37;
				break;
			case 1073741905:
				keyCode1 = 40;
				break;
			case 1073741906:
				keyCode1 = 38;
				break;
			case 1073741907:
				keyCode1 = 144;
				break;
			case 1073741908:
				keyCode1 = 111;
				break;
			case 1073741909:
				keyCode1 = 106;
				break;
			case 1073741910:
				keyCode1 = 109;
				break;
			case 1073741911:
				keyCode1 = 107;
				break;
			case 1073741912:
				keyCode1 = 108;
				break;
			case 1073741913:
				keyCode1 = 97;
				break;
			case 1073741914:
				keyCode1 = 98;
				break;
			case 1073741915:
				keyCode1 = 99;
				break;
			case 1073741916:
				keyCode1 = 100;
				break;
			case 1073741917:
				keyCode1 = 101;
				break;
			case 1073741918:
				keyCode1 = 102;
				break;
			case 1073741919:
				keyCode1 = 103;
				break;
			case 1073741920:
				keyCode1 = 104;
				break;
			case 1073741921:
				keyCode1 = 105;
				break;
			case 1073741922:
				keyCode1 = 96;
				break;
			case 1073741923:
				keyCode1 = 110;
				break;
			case 1073741925:
				keyCode1 = 302;
				break;
			case 1073741928:
				keyCode1 = 124;
				break;
			case 1073741929:
				keyCode1 = 125;
				break;
			case 1073741930:
				keyCode1 = 126;
				break;
			case 1073741982:
				keyCode1 = 13;
				break;
			case 1073742044:
				keyCode1 = 110;
				break;
			case 1073742048:
				keyCode1 = 17;
				break;
			case 1073742049:
				keyCode1 = 16;
				break;
			case 1073742050:
				keyCode1 = 18;
				break;
			case 1073742051:
				keyCode1 = 15;
				break;
			case 1073742052:
				keyCode1 = 17;
				break;
			case 1073742053:
				keyCode1 = 16;
				break;
			case 1073742054:
				keyCode1 = 18;
				break;
			case 1073742055:
				keyCode1 = 15;
				break;
			default:
				keyCode1 = keyCode;
			}
			var charCode = openfl_ui_Keyboard.__getCharCode(keyCode1,lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_shiftKey(modifier));
			var event = new openfl_events_KeyboardEvent(type,true,false,charCode,keyCode1,keyLocation,this.__macKeyboard?lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_ctrlKey(modifier) || lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_metaKey(modifier):lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_ctrlKey(modifier),lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_altKey(modifier),lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_shiftKey(modifier),lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_ctrlKey(modifier),lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_metaKey(modifier));
			stack.reverse();
			this.__fireEvent(event,stack);
		}
	}
	,__onMouse: function(type,x,y,button) {
		if(button > 2) return;
		this.__mouseX = x;
		this.__mouseY = y;
		var stack = [];
		var target = null;
		var targetPoint = new openfl_geom_Point(x,y);
		if(this.__hitTest(x,y,true,stack,true)) target = stack[stack.length - 1]; else {
			target = this;
			stack = [this];
		}
		if(target == null) target = this;
		if(type == openfl_events_MouseEvent.MOUSE_DOWN) {
			if(target.get_tabEnabled()) this.set_focus(target); else this.set_focus(null);
		}
		this.__fireEvent(openfl_events_MouseEvent.__create(type,button,this.__mouseX,this.__mouseY,target == this?targetPoint:target.globalToLocal(targetPoint),target),stack);
		var clickType;
		switch(type) {
		case "mouseUp":
			clickType = openfl_events_MouseEvent.CLICK;
			break;
		case "middleMouseUp":
			clickType = openfl_events_MouseEvent.MIDDLE_CLICK;
			break;
		case "rightMouseUp":
			clickType = openfl_events_MouseEvent.RIGHT_CLICK;
			break;
		default:
			clickType = null;
		}
		if(clickType != null) {
			this.__fireEvent(openfl_events_MouseEvent.__create(clickType,button,this.__mouseX,this.__mouseY,target == this?targetPoint:target.globalToLocal(targetPoint),target),stack);
			if(type == openfl_events_MouseEvent.MOUSE_UP && (js_Boot.__cast(target , openfl_display_InteractiveObject)).doubleClickEnabled) {
				var currentTime = openfl_Lib.getTimer();
				if(currentTime - this.__lastClickTime < 500) {
					this.__fireEvent(openfl_events_MouseEvent.__create(openfl_events_MouseEvent.DOUBLE_CLICK,button,this.__mouseX,this.__mouseY,target == this?targetPoint:target.globalToLocal(targetPoint),target),stack);
					this.__lastClickTime = 0;
				} else this.__lastClickTime = currentTime;
			}
		}
		var cursor = null;
		var _g = 0;
		while(_g < stack.length) {
			var target1 = stack[_g];
			++_g;
			cursor = target1.__getCursor();
			if(cursor != null) {
				lime_ui_Mouse.set_cursor(cursor);
				break;
			}
		}
		if(cursor == null) lime_ui_Mouse.set_cursor(lime_ui_MouseCursor.ARROW);
		var _g1 = 0;
		var _g11 = this.__mouseOutStack;
		while(_g1 < _g11.length) {
			var target2 = _g11[_g1];
			++_g1;
			if(HxOverrides.indexOf(stack,target2,0) == -1) {
				HxOverrides.remove(this.__mouseOutStack,target2);
				var localPoint = target2.globalToLocal(targetPoint);
				target2.__dispatchEvent(new openfl_events_MouseEvent(openfl_events_MouseEvent.MOUSE_OUT,false,false,localPoint.x,localPoint.y,target2));
			}
		}
		var _g2 = 0;
		while(_g2 < stack.length) {
			var target3 = stack[_g2];
			++_g2;
			if(HxOverrides.indexOf(this.__mouseOutStack,target3,0) == -1) {
				if(target3.hasEventListener(openfl_events_MouseEvent.MOUSE_OVER)) {
					var localPoint1 = target3.globalToLocal(targetPoint);
					target3.__dispatchEvent(new openfl_events_MouseEvent(openfl_events_MouseEvent.MOUSE_OVER,false,false,localPoint1.x,localPoint1.y,target3));
				}
				if(target3.hasEventListener(openfl_events_MouseEvent.MOUSE_OUT)) this.__mouseOutStack.push(target3);
			}
		}
		if(this.__dragObject != null) this.__drag(targetPoint);
	}
	,__onMouseWheel: function(deltaX,deltaY) {
		var x = this.__mouseX;
		var y = this.__mouseY;
		var stack = [];
		if(!this.__hitTest(x,y,false,stack,true)) stack = [this];
		var target = stack[stack.length - 1];
		var targetPoint = new openfl_geom_Point(x,y);
		var delta = deltaY | 0;
		this.__fireEvent(openfl_events_MouseEvent.__create(openfl_events_MouseEvent.MOUSE_WHEEL,0,this.__mouseX,this.__mouseY,target == this?targetPoint:target.globalToLocal(targetPoint),target,delta),stack);
	}
	,__onTouch: function(type,touch) {
		var point = new openfl_geom_Point(touch.x * this.stageWidth,touch.y * this.stageHeight);
		this.__mouseX = point.x;
		this.__mouseY = point.y;
		var __stack = [];
		if(this.__hitTest(touch.x,touch.y,false,__stack,true)) {
			var target = __stack[__stack.length - 1];
			if(target == null) target = this;
			var localPoint = target.globalToLocal(point);
			var touchEvent = openfl_events_TouchEvent.__create(type,null,this.__mouseX,this.__mouseY,localPoint,target);
			touchEvent.touchPointID = touch.id;
			touchEvent.isPrimaryTouchPoint = true;
			this.__fireEvent(touchEvent,__stack);
		} else {
			var touchEvent1 = openfl_events_TouchEvent.__create(type,null,this.__mouseX,this.__mouseY,point,this);
			touchEvent1.touchPointID = touch.id;
			touchEvent1.isPrimaryTouchPoint = true;
			this.__fireEvent(touchEvent1,[this.stage]);
		}
	}
	,__startDrag: function(sprite,lockCenter,bounds) {
		if(bounds == null) this.__dragBounds = null; else this.__dragBounds = bounds.clone();
		this.__dragObject = sprite;
		if(this.__dragObject != null) {
			if(lockCenter) {
				this.__dragOffsetX = -this.__dragObject.get_width() / 2;
				this.__dragOffsetY = -this.__dragObject.get_height() / 2;
			} else {
				var mouse = new openfl_geom_Point(this.get_mouseX(),this.get_mouseY());
				var parent = this.__dragObject.parent;
				if(parent != null) mouse = parent.globalToLocal(mouse);
				this.__dragOffsetX = this.__dragObject.get_x() - mouse.x;
				this.__dragOffsetY = this.__dragObject.get_y() - mouse.y;
			}
		}
	}
	,__stopDrag: function(sprite) {
		this.__dragBounds = null;
		this.__dragObject = null;
	}
	,__update: function(transformOnly,updateChildren,maskGrahpics) {
		if(transformOnly) {
			if(openfl_display_DisplayObject.__worldTransformDirty > 0) {
				openfl_display_DisplayObjectContainer.prototype.__update.call(this,true,updateChildren,maskGrahpics);
				if(updateChildren) {
					openfl_display_DisplayObject.__worldTransformDirty = 0;
					this.__dirty = true;
				}
			}
		} else if(openfl_display_DisplayObject.__worldTransformDirty > 0 || this.__dirty || openfl_display_DisplayObject.__worldRenderDirty > 0) {
			openfl_display_DisplayObjectContainer.prototype.__update.call(this,false,updateChildren,maskGrahpics);
			if(updateChildren) {
				openfl_display_DisplayObject.__worldTransformDirty = 0;
				openfl_display_DisplayObject.__worldRenderDirty = 0;
				this.__dirty = false;
			}
		}
	}
	,get_mouseX: function() {
		return this.__mouseX;
	}
	,get_mouseY: function() {
		return this.__mouseY;
	}
	,set_color: function(value) {
		var r = (value & 16711680) >>> 16;
		var g = (value & 65280) >>> 8;
		var b = value & 255;
		this.__colorSplit = [r / 255,g / 255,b / 255];
		this.__colorString = "#" + StringTools.hex(value,6);
		return this.__color = value;
	}
	,get_focus: function() {
		return this.__focus;
	}
	,set_focus: function(value) {
		if(value != this.__focus) {
			var oldFocus = this.__focus;
			this.__focus = value;
			if(oldFocus != null) {
				var event = new openfl_events_FocusEvent(openfl_events_FocusEvent.FOCUS_OUT,true,false,this.__focus,false,0);
				this.__stack = [];
				oldFocus.__getInteractive(this.__stack);
				this.__stack.reverse();
				this.__fireEvent(event,this.__stack);
			}
			if(this.__focus != null) {
				var event1 = new openfl_events_FocusEvent(openfl_events_FocusEvent.FOCUS_IN,true,false,oldFocus,false,0);
				this.__stack = [];
				value.__getInteractive(this.__stack);
				this.__stack.reverse();
				this.__fireEvent(event1,this.__stack);
			}
		}
		return this.__focus;
	}
	,__class__: openfl_display_Stage
	,__properties__: $extend(openfl_display_DisplayObjectContainer.prototype.__properties__,{set_focus:"set_focus",get_focus:"get_focus",set_color:"set_color"})
});
var openfl_display_Stage3D = function() {
	openfl_events_EventDispatcher.call(this);
};
$hxClasses["openfl.display.Stage3D"] = openfl_display_Stage3D;
openfl_display_Stage3D.__name__ = true;
openfl_display_Stage3D.__super__ = openfl_events_EventDispatcher;
openfl_display_Stage3D.prototype = $extend(openfl_events_EventDispatcher.prototype,{
	__class__: openfl_display_Stage3D
});
var openfl_display_StageAlign = $hxClasses["openfl.display.StageAlign"] = { __ename__ : true, __constructs__ : ["TOP_RIGHT","TOP_LEFT","TOP","RIGHT","LEFT","BOTTOM_RIGHT","BOTTOM_LEFT","BOTTOM"] };
openfl_display_StageAlign.TOP_RIGHT = ["TOP_RIGHT",0];
openfl_display_StageAlign.TOP_RIGHT.toString = $estr;
openfl_display_StageAlign.TOP_RIGHT.__enum__ = openfl_display_StageAlign;
openfl_display_StageAlign.TOP_LEFT = ["TOP_LEFT",1];
openfl_display_StageAlign.TOP_LEFT.toString = $estr;
openfl_display_StageAlign.TOP_LEFT.__enum__ = openfl_display_StageAlign;
openfl_display_StageAlign.TOP = ["TOP",2];
openfl_display_StageAlign.TOP.toString = $estr;
openfl_display_StageAlign.TOP.__enum__ = openfl_display_StageAlign;
openfl_display_StageAlign.RIGHT = ["RIGHT",3];
openfl_display_StageAlign.RIGHT.toString = $estr;
openfl_display_StageAlign.RIGHT.__enum__ = openfl_display_StageAlign;
openfl_display_StageAlign.LEFT = ["LEFT",4];
openfl_display_StageAlign.LEFT.toString = $estr;
openfl_display_StageAlign.LEFT.__enum__ = openfl_display_StageAlign;
openfl_display_StageAlign.BOTTOM_RIGHT = ["BOTTOM_RIGHT",5];
openfl_display_StageAlign.BOTTOM_RIGHT.toString = $estr;
openfl_display_StageAlign.BOTTOM_RIGHT.__enum__ = openfl_display_StageAlign;
openfl_display_StageAlign.BOTTOM_LEFT = ["BOTTOM_LEFT",6];
openfl_display_StageAlign.BOTTOM_LEFT.toString = $estr;
openfl_display_StageAlign.BOTTOM_LEFT.__enum__ = openfl_display_StageAlign;
openfl_display_StageAlign.BOTTOM = ["BOTTOM",7];
openfl_display_StageAlign.BOTTOM.toString = $estr;
openfl_display_StageAlign.BOTTOM.__enum__ = openfl_display_StageAlign;
var openfl_display_StageDisplayState = $hxClasses["openfl.display.StageDisplayState"] = { __ename__ : true, __constructs__ : ["NORMAL","FULL_SCREEN","FULL_SCREEN_INTERACTIVE"] };
openfl_display_StageDisplayState.NORMAL = ["NORMAL",0];
openfl_display_StageDisplayState.NORMAL.toString = $estr;
openfl_display_StageDisplayState.NORMAL.__enum__ = openfl_display_StageDisplayState;
openfl_display_StageDisplayState.FULL_SCREEN = ["FULL_SCREEN",1];
openfl_display_StageDisplayState.FULL_SCREEN.toString = $estr;
openfl_display_StageDisplayState.FULL_SCREEN.__enum__ = openfl_display_StageDisplayState;
openfl_display_StageDisplayState.FULL_SCREEN_INTERACTIVE = ["FULL_SCREEN_INTERACTIVE",2];
openfl_display_StageDisplayState.FULL_SCREEN_INTERACTIVE.toString = $estr;
openfl_display_StageDisplayState.FULL_SCREEN_INTERACTIVE.__enum__ = openfl_display_StageDisplayState;
var openfl_display_StageQuality = $hxClasses["openfl.display.StageQuality"] = { __ename__ : true, __constructs__ : ["BEST","HIGH","MEDIUM","LOW"] };
openfl_display_StageQuality.BEST = ["BEST",0];
openfl_display_StageQuality.BEST.toString = $estr;
openfl_display_StageQuality.BEST.__enum__ = openfl_display_StageQuality;
openfl_display_StageQuality.HIGH = ["HIGH",1];
openfl_display_StageQuality.HIGH.toString = $estr;
openfl_display_StageQuality.HIGH.__enum__ = openfl_display_StageQuality;
openfl_display_StageQuality.MEDIUM = ["MEDIUM",2];
openfl_display_StageQuality.MEDIUM.toString = $estr;
openfl_display_StageQuality.MEDIUM.__enum__ = openfl_display_StageQuality;
openfl_display_StageQuality.LOW = ["LOW",3];
openfl_display_StageQuality.LOW.toString = $estr;
openfl_display_StageQuality.LOW.__enum__ = openfl_display_StageQuality;
var openfl_display_StageScaleMode = $hxClasses["openfl.display.StageScaleMode"] = { __ename__ : true, __constructs__ : ["SHOW_ALL","NO_SCALE","NO_BORDER","EXACT_FIT"] };
openfl_display_StageScaleMode.SHOW_ALL = ["SHOW_ALL",0];
openfl_display_StageScaleMode.SHOW_ALL.toString = $estr;
openfl_display_StageScaleMode.SHOW_ALL.__enum__ = openfl_display_StageScaleMode;
openfl_display_StageScaleMode.NO_SCALE = ["NO_SCALE",1];
openfl_display_StageScaleMode.NO_SCALE.toString = $estr;
openfl_display_StageScaleMode.NO_SCALE.__enum__ = openfl_display_StageScaleMode;
openfl_display_StageScaleMode.NO_BORDER = ["NO_BORDER",2];
openfl_display_StageScaleMode.NO_BORDER.toString = $estr;
openfl_display_StageScaleMode.NO_BORDER.__enum__ = openfl_display_StageScaleMode;
openfl_display_StageScaleMode.EXACT_FIT = ["EXACT_FIT",3];
openfl_display_StageScaleMode.EXACT_FIT.toString = $estr;
openfl_display_StageScaleMode.EXACT_FIT.__enum__ = openfl_display_StageScaleMode;
var openfl_display_Tilesheet = function() { };
$hxClasses["openfl.display.Tilesheet"] = openfl_display_Tilesheet;
openfl_display_Tilesheet.__name__ = true;
openfl_display_Tilesheet.prototype = {
	__class__: openfl_display_Tilesheet
};
var openfl_display_TriangleCulling = $hxClasses["openfl.display.TriangleCulling"] = { __ename__ : true, __constructs__ : ["NEGATIVE","NONE","POSITIVE"] };
openfl_display_TriangleCulling.NEGATIVE = ["NEGATIVE",0];
openfl_display_TriangleCulling.NEGATIVE.toString = $estr;
openfl_display_TriangleCulling.NEGATIVE.__enum__ = openfl_display_TriangleCulling;
openfl_display_TriangleCulling.NONE = ["NONE",1];
openfl_display_TriangleCulling.NONE.toString = $estr;
openfl_display_TriangleCulling.NONE.__enum__ = openfl_display_TriangleCulling;
openfl_display_TriangleCulling.POSITIVE = ["POSITIVE",2];
openfl_display_TriangleCulling.POSITIVE.toString = $estr;
openfl_display_TriangleCulling.POSITIVE.__enum__ = openfl_display_TriangleCulling;
var openfl_display_Window = function(config) {
	lime_ui_Window.call(this,config);
};
$hxClasses["openfl.display.Window"] = openfl_display_Window;
openfl_display_Window.__name__ = true;
openfl_display_Window.__super__ = lime_ui_Window;
openfl_display_Window.prototype = $extend(lime_ui_Window.prototype,{
	create: function(application) {
		lime_ui_Window.prototype.create.call(this,application);
		this.stage = new openfl_display_Stage(this,Object.prototype.hasOwnProperty.call(this.config,"background")?this.config.background:16777215);
		application.addModule(this.stage);
	}
	,__class__: openfl_display_Window
});
var openfl_events_Event = function(type,bubbles,cancelable) {
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = false;
	this.type = type;
	this.bubbles = bubbles;
	this.cancelable = cancelable;
	this.eventPhase = openfl_events_EventPhase.AT_TARGET;
};
$hxClasses["openfl.events.Event"] = openfl_events_Event;
openfl_events_Event.__name__ = true;
openfl_events_Event.prototype = {
	stopImmediatePropagation: function() {
		this.__isCancelled = true;
		this.__isCancelledNow = true;
	}
	,stopPropagation: function() {
		this.__isCancelled = true;
	}
	,__class__: openfl_events_Event
};
var openfl_events_TextEvent = function(type,bubbles,cancelable,text) {
	if(text == null) text = "";
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = false;
	openfl_events_Event.call(this,type,bubbles,cancelable);
	this.text = text;
};
$hxClasses["openfl.events.TextEvent"] = openfl_events_TextEvent;
openfl_events_TextEvent.__name__ = true;
openfl_events_TextEvent.__super__ = openfl_events_Event;
openfl_events_TextEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: openfl_events_TextEvent
});
var openfl_events_ErrorEvent = function(type,bubbles,cancelable,text,id) {
	if(id == null) id = 0;
	if(text == null) text = "";
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = false;
	openfl_events_TextEvent.call(this,type,bubbles,cancelable,text);
	this.errorID = id;
};
$hxClasses["openfl.events.ErrorEvent"] = openfl_events_ErrorEvent;
openfl_events_ErrorEvent.__name__ = true;
openfl_events_ErrorEvent.__super__ = openfl_events_TextEvent;
openfl_events_ErrorEvent.prototype = $extend(openfl_events_TextEvent.prototype,{
	__class__: openfl_events_ErrorEvent
});
var openfl_events__$EventDispatcher_Listener = function(callback,useCapture,priority) {
	this.callback = callback;
	this.useCapture = useCapture;
	this.priority = priority;
};
$hxClasses["openfl.events._EventDispatcher.Listener"] = openfl_events__$EventDispatcher_Listener;
openfl_events__$EventDispatcher_Listener.__name__ = true;
openfl_events__$EventDispatcher_Listener.prototype = {
	match: function(callback,useCapture) {
		return Reflect.compareMethods(this.callback,callback) && this.useCapture == useCapture;
	}
	,__class__: openfl_events__$EventDispatcher_Listener
};
var openfl_events_EventPhase = $hxClasses["openfl.events.EventPhase"] = { __ename__ : true, __constructs__ : ["CAPTURING_PHASE","AT_TARGET","BUBBLING_PHASE"] };
openfl_events_EventPhase.CAPTURING_PHASE = ["CAPTURING_PHASE",0];
openfl_events_EventPhase.CAPTURING_PHASE.toString = $estr;
openfl_events_EventPhase.CAPTURING_PHASE.__enum__ = openfl_events_EventPhase;
openfl_events_EventPhase.AT_TARGET = ["AT_TARGET",1];
openfl_events_EventPhase.AT_TARGET.toString = $estr;
openfl_events_EventPhase.AT_TARGET.__enum__ = openfl_events_EventPhase;
openfl_events_EventPhase.BUBBLING_PHASE = ["BUBBLING_PHASE",2];
openfl_events_EventPhase.BUBBLING_PHASE.toString = $estr;
openfl_events_EventPhase.BUBBLING_PHASE.__enum__ = openfl_events_EventPhase;
var openfl_events_FocusEvent = function(type,bubbles,cancelable,relatedObject,shiftKey,keyCode) {
	if(keyCode == null) keyCode = 0;
	if(shiftKey == null) shiftKey = false;
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = false;
	openfl_events_Event.call(this,type,bubbles,cancelable);
	this.keyCode = keyCode;
	this.shiftKey = shiftKey;
	this.relatedObject = relatedObject;
};
$hxClasses["openfl.events.FocusEvent"] = openfl_events_FocusEvent;
openfl_events_FocusEvent.__name__ = true;
openfl_events_FocusEvent.__super__ = openfl_events_Event;
openfl_events_FocusEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: openfl_events_FocusEvent
});
var openfl_events_FullScreenEvent = function(type,bubbles,cancelable,fullScreen,interactive) {
	if(interactive == null) interactive = false;
	if(fullScreen == null) fullScreen = false;
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = false;
	openfl_events_Event.call(this,type,bubbles,cancelable);
	this.fullScreen = fullScreen;
	this.interactive = interactive;
};
$hxClasses["openfl.events.FullScreenEvent"] = openfl_events_FullScreenEvent;
openfl_events_FullScreenEvent.__name__ = true;
openfl_events_FullScreenEvent.__super__ = openfl_events_Event;
openfl_events_FullScreenEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: openfl_events_FullScreenEvent
});
var openfl_events_GameInputEvent = function(type,bubbles,cancelable,device) {
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = true;
	openfl_events_Event.call(this,type,bubbles,cancelable);
	this.device = device;
};
$hxClasses["openfl.events.GameInputEvent"] = openfl_events_GameInputEvent;
openfl_events_GameInputEvent.__name__ = true;
openfl_events_GameInputEvent.__super__ = openfl_events_Event;
openfl_events_GameInputEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: openfl_events_GameInputEvent
});
var openfl_events_HTTPStatusEvent = function(type,bubbles,cancelable,status) {
	if(status == null) status = 0;
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = false;
	this.status = status;
	openfl_events_Event.call(this,type,bubbles,cancelable);
};
$hxClasses["openfl.events.HTTPStatusEvent"] = openfl_events_HTTPStatusEvent;
openfl_events_HTTPStatusEvent.__name__ = true;
openfl_events_HTTPStatusEvent.__super__ = openfl_events_Event;
openfl_events_HTTPStatusEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: openfl_events_HTTPStatusEvent
});
var openfl_events_IOErrorEvent = function(type,bubbles,cancelable,text,id) {
	if(id == null) id = 0;
	if(text == null) text = "";
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = true;
	openfl_events_ErrorEvent.call(this,type,bubbles,cancelable,text,id);
};
$hxClasses["openfl.events.IOErrorEvent"] = openfl_events_IOErrorEvent;
openfl_events_IOErrorEvent.__name__ = true;
openfl_events_IOErrorEvent.__super__ = openfl_events_ErrorEvent;
openfl_events_IOErrorEvent.prototype = $extend(openfl_events_ErrorEvent.prototype,{
	__class__: openfl_events_IOErrorEvent
});
var openfl_events_KeyboardEvent = function(type,bubbles,cancelable,charCodeValue,keyCodeValue,keyLocationValue,ctrlKeyValue,altKeyValue,shiftKeyValue,controlKeyValue,commandKeyValue) {
	if(commandKeyValue == null) commandKeyValue = false;
	if(controlKeyValue == null) controlKeyValue = false;
	if(shiftKeyValue == null) shiftKeyValue = false;
	if(altKeyValue == null) altKeyValue = false;
	if(ctrlKeyValue == null) ctrlKeyValue = false;
	if(keyCodeValue == null) keyCodeValue = 0;
	if(charCodeValue == null) charCodeValue = 0;
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = false;
	openfl_events_Event.call(this,type,bubbles,cancelable);
	this.charCode = charCodeValue;
	this.keyCode = keyCodeValue;
	if(keyLocationValue != null) this.keyLocation = keyLocationValue; else this.keyLocation = 0;
	this.ctrlKey = ctrlKeyValue;
	this.altKey = altKeyValue;
	this.shiftKey = shiftKeyValue;
	this.controlKey = controlKeyValue;
	this.commandKey = commandKeyValue;
};
$hxClasses["openfl.events.KeyboardEvent"] = openfl_events_KeyboardEvent;
openfl_events_KeyboardEvent.__name__ = true;
openfl_events_KeyboardEvent.__super__ = openfl_events_Event;
openfl_events_KeyboardEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: openfl_events_KeyboardEvent
});
var openfl_events_MouseEvent = function(type,bubbles,cancelable,localX,localY,relatedObject,ctrlKey,altKey,shiftKey,buttonDown,delta,commandKey,clickCount) {
	if(clickCount == null) clickCount = 0;
	if(commandKey == null) commandKey = false;
	if(delta == null) delta = 0;
	if(buttonDown == null) buttonDown = false;
	if(shiftKey == null) shiftKey = false;
	if(altKey == null) altKey = false;
	if(ctrlKey == null) ctrlKey = false;
	if(localY == null) localY = 0;
	if(localX == null) localX = 0;
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = true;
	openfl_events_Event.call(this,type,bubbles,cancelable);
	this.shiftKey = shiftKey;
	this.altKey = altKey;
	this.ctrlKey = ctrlKey;
	this.bubbles = bubbles;
	this.relatedObject = relatedObject;
	this.delta = delta;
	this.localX = localX;
	this.localY = localY;
	this.buttonDown = buttonDown;
	this.commandKey = commandKey;
	this.clickCount = clickCount;
};
$hxClasses["openfl.events.MouseEvent"] = openfl_events_MouseEvent;
openfl_events_MouseEvent.__name__ = true;
openfl_events_MouseEvent.__altKey = null;
openfl_events_MouseEvent.__buttonDown = null;
openfl_events_MouseEvent.__commandKey = null;
openfl_events_MouseEvent.__ctrlKey = null;
openfl_events_MouseEvent.__shiftKey = null;
openfl_events_MouseEvent.__create = function(type,button,stageX,stageY,local,target,delta) {
	if(delta == null) delta = 0;
	switch(type) {
	case "mouseDown":
		openfl_events_MouseEvent.__buttonDown = true;
		break;
	case "mouseUp":
		openfl_events_MouseEvent.__buttonDown = false;
		break;
	default:
	}
	var event = new openfl_events_MouseEvent(type,true,false,local.x,local.y,null,openfl_events_MouseEvent.__ctrlKey,openfl_events_MouseEvent.__altKey,openfl_events_MouseEvent.__shiftKey,openfl_events_MouseEvent.__buttonDown,delta,openfl_events_MouseEvent.__commandKey);
	event.stageX = stageX;
	event.stageY = stageY;
	event.target = target;
	return event;
};
openfl_events_MouseEvent.__super__ = openfl_events_Event;
openfl_events_MouseEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: openfl_events_MouseEvent
});
var openfl_events_ProgressEvent = function(type,bubbles,cancelable,bytesLoaded,bytesTotal) {
	if(bytesTotal == null) bytesTotal = 0;
	if(bytesLoaded == null) bytesLoaded = 0;
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = false;
	openfl_events_Event.call(this,type,bubbles,cancelable);
	this.bytesLoaded = bytesLoaded;
	this.bytesTotal = bytesTotal;
};
$hxClasses["openfl.events.ProgressEvent"] = openfl_events_ProgressEvent;
openfl_events_ProgressEvent.__name__ = true;
openfl_events_ProgressEvent.__super__ = openfl_events_Event;
openfl_events_ProgressEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: openfl_events_ProgressEvent
});
var openfl_events_SecurityErrorEvent = function(type,bubbles,cancelable,text,id) {
	if(id == null) id = 0;
	if(text == null) text = "";
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = false;
	openfl_events_ErrorEvent.call(this,type,bubbles,cancelable,text,id);
};
$hxClasses["openfl.events.SecurityErrorEvent"] = openfl_events_SecurityErrorEvent;
openfl_events_SecurityErrorEvent.__name__ = true;
openfl_events_SecurityErrorEvent.__super__ = openfl_events_ErrorEvent;
openfl_events_SecurityErrorEvent.prototype = $extend(openfl_events_ErrorEvent.prototype,{
	__class__: openfl_events_SecurityErrorEvent
});
var openfl_events_TouchEvent = function(type,bubbles,cancelable,localX,localY,sizeX,sizeY,relatedObject,ctrlKey,altKey,shiftKey,buttonDown,delta,commandKey,clickCount) {
	if(clickCount == null) clickCount = 0;
	if(commandKey == null) commandKey = false;
	if(delta == null) delta = 0;
	if(buttonDown == null) buttonDown = false;
	if(shiftKey == null) shiftKey = false;
	if(altKey == null) altKey = false;
	if(ctrlKey == null) ctrlKey = false;
	if(sizeY == null) sizeY = 1;
	if(sizeX == null) sizeX = 1;
	if(localY == null) localY = 0;
	if(localX == null) localX = 0;
	if(cancelable == null) cancelable = false;
	if(bubbles == null) bubbles = true;
	openfl_events_Event.call(this,type,bubbles,cancelable);
	this.shiftKey = shiftKey;
	this.altKey = altKey;
	this.ctrlKey = ctrlKey;
	this.bubbles = bubbles;
	this.relatedObject = relatedObject;
	this.delta = delta;
	this.localX = localX;
	this.localY = localY;
	this.sizeX = sizeX;
	this.sizeY = sizeY;
	this.buttonDown = buttonDown;
	this.commandKey = commandKey;
	this.pressure = 1;
	this.touchPointID = 0;
	this.isPrimaryTouchPoint = true;
};
$hxClasses["openfl.events.TouchEvent"] = openfl_events_TouchEvent;
openfl_events_TouchEvent.__name__ = true;
openfl_events_TouchEvent.__create = function(type,touch,stageX,stageY,local,target) {
	var evt = new openfl_events_TouchEvent(type,true,false,local.x,local.y,1,1,null,false,false,false,false,0,false,0);
	evt.stageX = stageX;
	evt.stageY = stageY;
	evt.target = target;
	return evt;
};
openfl_events_TouchEvent.__super__ = openfl_events_Event;
openfl_events_TouchEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: openfl_events_TouchEvent
});
var openfl_filters_BitmapFilter = function() {
	this.__saveLastFilter = false;
	this.__passes = 0;
};
$hxClasses["openfl.filters.BitmapFilter"] = openfl_filters_BitmapFilter;
openfl_filters_BitmapFilter.__name__ = true;
openfl_filters_BitmapFilter.__tmpRenderTexture = null;
openfl_filters_BitmapFilter.__expandBounds = function(filters,rect,matrix) {
	var r = openfl_geom_Rectangle.__temp;
	r.setEmpty();
	var _g = 0;
	while(_g < filters.length) {
		var filter = filters[_g];
		++_g;
		filter.__growBounds(r);
	}
	r.__transform(r,matrix);
	rect.__expand(r.x,r.y,r.width,r.height);
};
openfl_filters_BitmapFilter.__applyFilters = function(filters,renderSession,source,target,sourceRect,destPoint) {
	var same = target == source && target.__usingPingPongTexture;
	if(same) target.__pingPongTexture.useOldTexture = true;
	if(sourceRect == null) sourceRect = source.rect;
	var lastFilterOutput = null;
	var useLastFilter = false;
	var srcShader = source.__shader;
	var _g = 0;
	while(_g < filters.length) {
		var filter = filters[_g];
		++_g;
		useLastFilter = false;
		if(filter.__saveLastFilter) {
			target.__pingPongTexture.swap();
			target.__drawGL(renderSession,source,null,null,null,sourceRect,true,!target.__usingPingPongTexture,true);
			lastFilterOutput = target.__pingPongTexture.get_oldRenderTexture();
			target.__pingPongTexture.set_oldRenderTexture(openfl_filters_BitmapFilter.__tmpRenderTexture);
		}
		var _g2 = 0;
		var _g1 = filter.__passes;
		while(_g2 < _g1) {
			var pass = _g2++;
			useLastFilter = filter.__saveLastFilter && filter.__useLastFilter(pass);
			if(same && !useLastFilter) target.__pingPongTexture.swap();
			if(useLastFilter) {
				openfl_filters_BitmapFilter.__tmpRenderTexture = target.__pingPongTexture.get_oldRenderTexture();
				target.__pingPongTexture.set_oldRenderTexture(lastFilterOutput);
			}
			source.__shader = filter.__preparePass(pass);
			target.__drawGL(renderSession,source,null,null,null,sourceRect,true,!target.__usingPingPongTexture,!useLastFilter);
		}
	}
	source.__shader = srcShader;
	if(same) target.__pingPongTexture.useOldTexture = false;
};
openfl_filters_BitmapFilter.prototype = {
	__growBounds: function(rect) {
	}
	,__preparePass: function(pass) {
		return null;
	}
	,__useLastFilter: function(pass) {
		return false;
	}
	,__class__: openfl_filters_BitmapFilter
};
var openfl_geom_Matrix3D = function() { };
$hxClasses["openfl.geom.Matrix3D"] = openfl_geom_Matrix3D;
openfl_geom_Matrix3D.__name__ = true;
openfl_geom_Matrix3D.prototype = {
	__class__: openfl_geom_Matrix3D
};
var openfl_geom_Transform = function(displayObject) {
	this.__colorTransform = new openfl_geom_ColorTransform();
	this.concatenatedColorTransform = new openfl_geom_ColorTransform();
	this.pixelBounds = new openfl_geom_Rectangle();
	this.__displayObject = displayObject;
	this.__hasMatrix = true;
};
$hxClasses["openfl.geom.Transform"] = openfl_geom_Transform;
openfl_geom_Transform.__name__ = true;
openfl_geom_Transform.prototype = {
	get_colorTransform: function() {
		return this.__colorTransform;
	}
	,__class__: openfl_geom_Transform
	,__properties__: {get_colorTransform:"get_colorTransform"}
};
var openfl_geom_Vector3D = function() { };
$hxClasses["openfl.geom.Vector3D"] = openfl_geom_Vector3D;
openfl_geom_Vector3D.__name__ = true;
var openfl_media_ID3Info = function() { };
$hxClasses["openfl.media.ID3Info"] = openfl_media_ID3Info;
openfl_media_ID3Info.__name__ = true;
var openfl_media_Sound = function(stream,context) {
	openfl_events_EventDispatcher.call(this,this);
	this.bytesLoaded = 0;
	this.bytesTotal = 0;
	this.id3 = null;
	this.isBuffering = false;
	this.url = null;
	if(stream != null) this.load(stream,context);
};
$hxClasses["openfl.media.Sound"] = openfl_media_Sound;
openfl_media_Sound.__name__ = true;
openfl_media_Sound.__super__ = openfl_events_EventDispatcher;
openfl_media_Sound.prototype = $extend(openfl_events_EventDispatcher.prototype,{
	load: function(stream,context) {
		this.url = stream.url;
		this.__soundID = haxe_io_Path.withoutExtension(stream.url);
		if(!openfl_media_Sound.__registeredSounds.exists(this.__soundID)) {
			openfl_media_Sound.__registeredSounds.set(this.__soundID,true);
			createjs.Sound.addEventListener("fileload",$bind(this,this.SoundJS_onFileLoad));
			createjs.Sound.addEventListener("fileerror",$bind(this,this.SoundJS_onFileError));
			createjs.Sound.registerSound(this.url,this.__soundID);
		} else this.dispatchEvent(new openfl_events_Event(openfl_events_Event.COMPLETE));
	}
	,SoundJS_onFileLoad: function(event) {
		if(event.id == this.__soundID) {
			createjs.Sound.removeEventListener("fileload",$bind(this,this.SoundJS_onFileLoad));
			createjs.Sound.removeEventListener("fileerror",$bind(this,this.SoundJS_onFileError));
			this.dispatchEvent(new openfl_events_Event(openfl_events_Event.COMPLETE));
		}
	}
	,SoundJS_onFileError: function(event) {
		if(event.id == this.__soundID) {
			createjs.Sound.removeEventListener("fileload",$bind(this,this.SoundJS_onFileLoad));
			createjs.Sound.removeEventListener("fileerror",$bind(this,this.SoundJS_onFileError));
			this.dispatchEvent(new openfl_events_IOErrorEvent(openfl_events_IOErrorEvent.IO_ERROR));
		}
	}
	,__class__: openfl_media_Sound
});
var openfl_media_SoundLoaderContext = function() { };
$hxClasses["openfl.media.SoundLoaderContext"] = openfl_media_SoundLoaderContext;
openfl_media_SoundLoaderContext.__name__ = true;
var openfl_media_SoundTransform = function(vol,panning) {
	if(panning == null) panning = 0;
	if(vol == null) vol = 1;
	this.volume = vol;
	this.pan = panning;
	this.leftToLeft = 0;
	this.leftToRight = 0;
	this.rightToLeft = 0;
	this.rightToRight = 0;
};
$hxClasses["openfl.media.SoundTransform"] = openfl_media_SoundTransform;
openfl_media_SoundTransform.__name__ = true;
openfl_media_SoundTransform.prototype = {
	__class__: openfl_media_SoundTransform
};
var openfl_net_URLLoader = function(request) {
	openfl_events_EventDispatcher.call(this);
	this.bytesLoaded = 0;
	this.bytesTotal = 0;
	this.set_dataFormat(openfl_net_URLLoaderDataFormat.TEXT);
	if(request != null) this.load(request);
};
$hxClasses["openfl.net.URLLoader"] = openfl_net_URLLoader;
openfl_net_URLLoader.__name__ = true;
openfl_net_URLLoader.__super__ = openfl_events_EventDispatcher;
openfl_net_URLLoader.prototype = $extend(openfl_events_EventDispatcher.prototype,{
	getData: function() {
		return null;
	}
	,load: function(request) {
		this.requestUrl(request.url,request.method,request.data,request.formatRequestHeaders());
	}
	,registerEvents: function(subject) {
		var self = this;
		if(typeof XMLHttpRequestProgressEvent != "undefined") subject.addEventListener("progress",$bind(this,this.onProgress),false);
		subject.onreadystatechange = function() {
			if(subject.readyState != 4) return;
			var s;
			try {
				s = subject.status;
			} catch( e ) {
				if (e instanceof js__$Boot_HaxeError) e = e.val;
				s = null;
			}
			if(s == undefined) s = null;
			if(s != null) self.onStatus(s);
			if(s != null && s >= 200 && s < 400) self.onData(subject.response); else if(s == null) self.onError("Failed to connect or resolve host"); else if(s == 12029) self.onError("Failed to connect to host"); else if(s == 12007) self.onError("Unknown host"); else if(s == 0) {
				self.onError("Unable to make request (may be blocked due to cross-domain permissions)");
				self.onSecurityError("Unable to make request (may be blocked due to cross-domain permissions)");
			} else self.onError("Http Error #" + subject.status);
		};
	}
	,requestUrl: function(url,method,data,requestHeaders) {
		var xmlHttpRequest = new XMLHttpRequest();
		this.registerEvents(xmlHttpRequest);
		var uri = "";
		if(js_Boot.__instanceof(data,lime_utils_ByteArray)) {
			var data1 = data;
			var _g = this.dataFormat;
			switch(_g[1]) {
			case 0:
				uri = data1.data.buffer;
				break;
			default:
				uri = data1.readUTFBytes(data1.length);
			}
		} else if(js_Boot.__instanceof(data,openfl_net_URLVariables)) {
			var data2 = data;
			var _g1 = 0;
			var _g11 = Reflect.fields(data2);
			while(_g1 < _g11.length) {
				var p = _g11[_g1];
				++_g1;
				if(uri.length != 0) uri += "&";
				uri += encodeURIComponent(p) + "=" + StringTools.urlEncode(Reflect.field(data2,p));
			}
		} else if(data != null) uri = data.toString();
		try {
			if(method == "GET" && uri != null && uri != "") {
				var question = url.split("?").length <= 1;
				xmlHttpRequest.open(method,url + (question?"?":"&") + Std.string(uri),true);
				uri = "";
			} else xmlHttpRequest.open(method,url,true);
		} catch( e ) {
			if (e instanceof js__$Boot_HaxeError) e = e.val;
			this.onError(e.toString());
			return;
		}
		var _g2 = this.dataFormat;
		switch(_g2[1]) {
		case 0:
			xmlHttpRequest.responseType = "arraybuffer";
			break;
		default:
		}
		var _g3 = 0;
		while(_g3 < requestHeaders.length) {
			var header = requestHeaders[_g3];
			++_g3;
			xmlHttpRequest.setRequestHeader(header.name,header.value);
		}
		xmlHttpRequest.send(uri);
		this.onOpen();
		this.getData = function() {
			if(xmlHttpRequest.response != null) return xmlHttpRequest.response; else return xmlHttpRequest.responseText;
		};
	}
	,onData: function(_) {
		var content = this.getData();
		var _g = this.dataFormat;
		switch(_g[1]) {
		case 0:
			this.data = lime_utils_ByteArray.__ofBuffer(content);
			break;
		default:
			this.data = Std.string(content);
		}
		var evt = new openfl_events_Event(openfl_events_Event.COMPLETE);
		evt.currentTarget = this;
		this.dispatchEvent(evt);
	}
	,onError: function(msg) {
		var evt = new openfl_events_IOErrorEvent(openfl_events_IOErrorEvent.IO_ERROR);
		evt.text = msg;
		evt.currentTarget = this;
		this.dispatchEvent(evt);
	}
	,onOpen: function() {
		var evt = new openfl_events_Event(openfl_events_Event.OPEN);
		evt.currentTarget = this;
		this.dispatchEvent(evt);
	}
	,onProgress: function(event) {
		var evt = new openfl_events_ProgressEvent(openfl_events_ProgressEvent.PROGRESS);
		evt.currentTarget = this;
		evt.bytesLoaded = event.loaded;
		evt.bytesTotal = event.total;
		this.dispatchEvent(evt);
	}
	,onSecurityError: function(msg) {
		var evt = new openfl_events_SecurityErrorEvent(openfl_events_SecurityErrorEvent.SECURITY_ERROR);
		evt.text = msg;
		evt.currentTarget = this;
		this.dispatchEvent(evt);
	}
	,onStatus: function(status) {
		var evt = new openfl_events_HTTPStatusEvent(openfl_events_HTTPStatusEvent.HTTP_STATUS,false,false,status);
		evt.currentTarget = this;
		this.dispatchEvent(evt);
	}
	,set_dataFormat: function(inputVal) {
		if(inputVal == openfl_net_URLLoaderDataFormat.BINARY && !Reflect.hasField(window,"ArrayBuffer")) this.dataFormat = openfl_net_URLLoaderDataFormat.TEXT; else this.dataFormat = inputVal;
		return this.dataFormat;
	}
	,__class__: openfl_net_URLLoader
	,__properties__: {set_dataFormat:"set_dataFormat"}
});
var openfl_net_URLLoaderDataFormat = $hxClasses["openfl.net.URLLoaderDataFormat"] = { __ename__ : true, __constructs__ : ["BINARY","TEXT","VARIABLES"] };
openfl_net_URLLoaderDataFormat.BINARY = ["BINARY",0];
openfl_net_URLLoaderDataFormat.BINARY.toString = $estr;
openfl_net_URLLoaderDataFormat.BINARY.__enum__ = openfl_net_URLLoaderDataFormat;
openfl_net_URLLoaderDataFormat.TEXT = ["TEXT",1];
openfl_net_URLLoaderDataFormat.TEXT.toString = $estr;
openfl_net_URLLoaderDataFormat.TEXT.__enum__ = openfl_net_URLLoaderDataFormat;
openfl_net_URLLoaderDataFormat.VARIABLES = ["VARIABLES",2];
openfl_net_URLLoaderDataFormat.VARIABLES.toString = $estr;
openfl_net_URLLoaderDataFormat.VARIABLES.__enum__ = openfl_net_URLLoaderDataFormat;
var openfl_net_URLRequest = function(inURL) {
	if(inURL != null) this.url = inURL;
	this.requestHeaders = [];
	this.method = "GET";
	this.contentType = null;
};
$hxClasses["openfl.net.URLRequest"] = openfl_net_URLRequest;
openfl_net_URLRequest.__name__ = true;
openfl_net_URLRequest.prototype = {
	formatRequestHeaders: function() {
		var res = this.requestHeaders;
		if(res == null) res = [];
		if(this.method == "GET" || this.data == null) return res;
		if(typeof(this.data) == "string" || js_Boot.__instanceof(this.data,lime_utils_ByteArray)) {
			res = res.slice();
			res.push(new openfl_net_URLRequestHeader("Content-Type",this.contentType != null?this.contentType:"application/x-www-form-urlencoded"));
		}
		return res;
	}
	,__class__: openfl_net_URLRequest
};
var openfl_net_URLRequestHeader = function(name,value) {
	if(value == null) value = "";
	if(name == null) name = "";
	this.name = name;
	this.value = value;
};
$hxClasses["openfl.net.URLRequestHeader"] = openfl_net_URLRequestHeader;
openfl_net_URLRequestHeader.__name__ = true;
openfl_net_URLRequestHeader.prototype = {
	__class__: openfl_net_URLRequestHeader
};
var openfl_net_URLVariables = function() { };
$hxClasses["openfl.net.URLVariables"] = openfl_net_URLVariables;
openfl_net_URLVariables.__name__ = true;
var openfl_text_AntiAliasType = $hxClasses["openfl.text.AntiAliasType"] = { __ename__ : true, __constructs__ : ["ADVANCED","NORMAL"] };
openfl_text_AntiAliasType.ADVANCED = ["ADVANCED",0];
openfl_text_AntiAliasType.ADVANCED.toString = $estr;
openfl_text_AntiAliasType.ADVANCED.__enum__ = openfl_text_AntiAliasType;
openfl_text_AntiAliasType.NORMAL = ["NORMAL",1];
openfl_text_AntiAliasType.NORMAL.toString = $estr;
openfl_text_AntiAliasType.NORMAL.__enum__ = openfl_text_AntiAliasType;
var openfl_text_GridFitType = $hxClasses["openfl.text.GridFitType"] = { __ename__ : true, __constructs__ : ["NONE","PIXEL","SUBPIXEL"] };
openfl_text_GridFitType.NONE = ["NONE",0];
openfl_text_GridFitType.NONE.toString = $estr;
openfl_text_GridFitType.NONE.__enum__ = openfl_text_GridFitType;
openfl_text_GridFitType.PIXEL = ["PIXEL",1];
openfl_text_GridFitType.PIXEL.toString = $estr;
openfl_text_GridFitType.PIXEL.__enum__ = openfl_text_GridFitType;
openfl_text_GridFitType.SUBPIXEL = ["SUBPIXEL",2];
openfl_text_GridFitType.SUBPIXEL.toString = $estr;
openfl_text_GridFitType.SUBPIXEL.__enum__ = openfl_text_GridFitType;
var openfl_text_TextField = function() {
	openfl_display_InteractiveObject.call(this);
	this.__caretIndex = -1;
	this.__graphics = new openfl_display_Graphics();
	this.__textEngine = new openfl__$internal_text_TextEngine(this);
	this.__layoutDirty = true;
	this.__tabEnabled = true;
	if(openfl_text_TextField.__defaultTextFormat == null) {
		openfl_text_TextField.__defaultTextFormat = new openfl_text_TextFormat("Times New Roman",12,0,false,false,false,"","",openfl_text_TextFormatAlign.LEFT,0,0,0,0);
		openfl_text_TextField.__defaultTextFormat.blockIndent = 0;
		openfl_text_TextField.__defaultTextFormat.bullet = false;
		openfl_text_TextField.__defaultTextFormat.letterSpacing = 0;
		openfl_text_TextField.__defaultTextFormat.kerning = false;
	}
	this.__textFormat = openfl_text_TextField.__defaultTextFormat.clone();
	this.__textEngine.textFormatRanges.push(new openfl__$internal_text_TextFormatRange(this.__textFormat,0,0));
	this.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,$bind(this,this.this_onMouseDown));
};
$hxClasses["openfl.text.TextField"] = openfl_text_TextField;
openfl_text_TextField.__name__ = true;
openfl_text_TextField.__defaultTextFormat = null;
openfl_text_TextField.__super__ = openfl_display_InteractiveObject;
openfl_text_TextField.prototype = $extend(openfl_display_InteractiveObject.prototype,{
	getCharBoundaries: function(charIndex) {
		if(charIndex < 0 || charIndex > this.__textEngine.text.length - 1) return null;
		this.__updateLayout();
		var _g = 0;
		var _g1 = this.__textEngine.layoutGroups;
		while(_g < _g1.length) {
			var group = _g1[_g];
			++_g;
			if(charIndex >= group.startIndex && charIndex <= group.endIndex) {
				var x = group.offsetX;
				var _g3 = 0;
				var _g2 = charIndex - group.startIndex;
				while(_g3 < _g2) {
					var i = _g3++;
					x += group.advances[i];
				}
				return new openfl_geom_Rectangle(x,group.offsetY,group.advances[charIndex - group.startIndex],group.ascent + group.descent);
			}
		}
		return null;
	}
	,replaceSelectedText: function(value) {
		if(value == "" && this.__selectionIndex == this.__caretIndex) return;
		var startIndex;
		if(this.__caretIndex < this.__selectionIndex) startIndex = this.__caretIndex; else startIndex = this.__selectionIndex;
		var endIndex;
		if(this.__caretIndex > this.__selectionIndex) endIndex = this.__caretIndex; else endIndex = this.__selectionIndex;
		this.replaceText(startIndex,endIndex,value);
		this.__caretIndex = startIndex + value.length;
		this.__selectionIndex = this.__caretIndex;
	}
	,replaceText: function(beginIndex,endIndex,newText) {
		if(endIndex < beginIndex || beginIndex < 0 || endIndex > this.__textEngine.text.length || newText == null) return;
		this.__textEngine.text = this.__textEngine.text.substring(0,beginIndex) + newText + this.__textEngine.text.substring(endIndex);
		var offset = newText.length - (endIndex - beginIndex);
		var i = 0;
		var range;
		while(i < this.__textEngine.textFormatRanges.length) {
			range = this.__textEngine.textFormatRanges[i];
			if(range.start <= beginIndex && range.end >= endIndex) {
				range.end += offset;
				i++;
			} else if(range.start >= beginIndex && range.end <= endIndex) {
				this.__textEngine.textFormatRanges.splice(i,1);
				offset -= range.end - range.start;
			} else if(range.start > beginIndex && range.start <= endIndex) {
				range.start += offset;
				i++;
			} else i++;
		}
		this.__dirty = true;
		this.__layoutDirty = true;
	}
	,__getBounds: function(rect,matrix) {
		this.__updateLayout();
		var bounds = openfl_geom_Rectangle.__temp;
		this.__textEngine.bounds.__transform(bounds,matrix);
		rect.__expand(bounds.x,bounds.y,bounds.width,bounds.height);
	}
	,__getCursor: function() {
		if(this.__textEngine.selectable) return lime_ui_MouseCursor.TEXT; else return null;
	}
	,__getPosition: function(x,y) {
		this.__updateLayout();
		x += this.get_scrollH();
		var _g1 = 0;
		var _g = this.get_scrollV() - 1;
		while(_g1 < _g) {
			var i = _g1++;
			y += this.__textEngine.lineHeights[i];
		}
		if(y > this.__textEngine.textHeight) y = this.__textEngine.textHeight;
		var firstGroup = true;
		var group;
		var nextGroup;
		var _g11 = 0;
		var _g2 = this.__textEngine.layoutGroups.length;
		while(_g11 < _g2) {
			var i1 = _g11++;
			group = this.__textEngine.layoutGroups[i1];
			if(i1 < this.__textEngine.layoutGroups.length - 1) nextGroup = this.__textEngine.layoutGroups[i1 + 1]; else nextGroup = null;
			if(firstGroup) {
				if(y < group.offsetY) y = group.offsetY;
				if(x < group.offsetX) x = group.offsetX;
				firstGroup = false;
			}
			if(y >= group.offsetY && y <= group.offsetY + group.height || nextGroup == null) {
				if(x >= group.offsetX && x <= group.offsetX + group.width || (nextGroup == null || nextGroup.lineIndex != group.lineIndex)) {
					var advance = 0.0;
					var _g3 = 0;
					var _g21 = group.advances.length;
					while(_g3 < _g21) {
						var i2 = _g3++;
						advance += group.advances[i2];
						if(x <= group.offsetX + advance) {
							if(x <= group.offsetX + (advance - group.advances[i2]) + group.advances[i2] / 2) return group.startIndex + i2; else if(group.startIndex + i2 < group.endIndex) return group.startIndex + i2 + 1; else return group.endIndex;
						}
					}
					return group.endIndex;
				}
			}
		}
		return this.__textEngine.text.length;
	}
	,__hitTest: function(x,y,shapeFlag,stack,interactiveOnly) {
		if(!this.get_visible() || this.__isMask || interactiveOnly && !this.mouseEnabled) return false;
		if(this.get_mask() != null && !this.get_mask().__hitTestMask(x,y)) return false;
		this.__getWorldTransform();
		this.__updateLayout();
		var px = this.__worldTransform.__transformInverseX(x,y);
		var py = this.__worldTransform.__transformInverseY(x,y);
		if(this.__textEngine.bounds.contains(px,py)) {
			if(stack != null) stack.push(this);
			return true;
		}
		return false;
	}
	,__hitTestMask: function(x,y) {
		this.__getWorldTransform();
		this.__updateLayout();
		var px = this.__worldTransform.__transformInverseX(x,y);
		var py = this.__worldTransform.__transformInverseY(x,y);
		if(this.__textEngine.bounds.contains(px,py)) return true;
		return false;
	}
	,__renderCairo: function(renderSession) {
		openfl__$internal_renderer_cairo_CairoTextField.render(this,renderSession);
		openfl_display_InteractiveObject.prototype.__renderCairo.call(this,renderSession);
	}
	,__renderCanvas: function(renderSession) {
		openfl__$internal_renderer_canvas_CanvasTextField.render(this,renderSession);
		if(this.__textEngine.antiAliasType == openfl_text_AntiAliasType.ADVANCED && this.__textEngine.gridFitType == openfl_text_GridFitType.PIXEL) {
			var smoothingEnabled = renderSession.context.imageSmoothingEnabled;
			if(smoothingEnabled) {
				renderSession.context.mozImageSmoothingEnabled = false;
				renderSession.context.msImageSmoothingEnabled = false;
				renderSession.context.imageSmoothingEnabled = false;
			}
			openfl_display_InteractiveObject.prototype.__renderCanvas.call(this,renderSession);
			if(smoothingEnabled) {
				renderSession.context.mozImageSmoothingEnabled = true;
				renderSession.context.msImageSmoothingEnabled = true;
				renderSession.context.imageSmoothingEnabled = true;
			}
		} else openfl_display_InteractiveObject.prototype.__renderCanvas.call(this,renderSession);
	}
	,__renderDOM: function(renderSession) {
		openfl__$internal_renderer_dom_DOMTextField.render(this,renderSession);
	}
	,__renderGL: function(renderSession) {
		if(this.__cacheAsBitmap) {
			this.__cacheGL(renderSession);
			return;
		}
		if(this.__scrollRect != null) renderSession.maskManager.pushRect(this.__scrollRect,this.__renderTransform);
		if(this.__mask != null && this.__maskGraphics != null && this.__maskGraphics.__commands.get_length() > 0) renderSession.maskManager.pushMask(this);
		openfl__$internal_renderer_canvas_CanvasTextField.render(this,renderSession);
		openfl__$internal_renderer_opengl_GLRenderer.renderBitmap(this,renderSession,this.__textEngine.antiAliasType != openfl_text_AntiAliasType.ADVANCED || this.__textEngine.gridFitType != openfl_text_GridFitType.PIXEL);
		if(this.__mask != null && this.__maskGraphics != null && this.__maskGraphics.__commands.get_length() > 0) renderSession.maskManager.popMask();
		if(this.__scrollRect != null) renderSession.maskManager.popRect();
	}
	,__startCursorTimer: function() {
		this.__cursorTimer = haxe_Timer.delay($bind(this,this.__startCursorTimer),600);
		this.__showCursor = !this.__showCursor;
		this.__dirty = true;
	}
	,__startTextInput: function() {
		if(this.__caretIndex < 0) {
			this.__caretIndex = this.__textEngine.text.length;
			this.__selectionIndex = this.__caretIndex;
		}
		if(this.stage != null) {
			this.stage.window.backend.setEnableTextEvents(true);
			if(!this.__inputEnabled) {
				this.stage.window.backend.setEnableTextEvents(true);
				if(!this.stage.window.onTextInput.has($bind(this,this.window_onTextInput))) {
					this.stage.window.onTextInput.add($bind(this,this.window_onTextInput));
					this.stage.window.onKeyDown.add($bind(this,this.window_onKeyDown));
				}
				this.__inputEnabled = true;
				this.__startCursorTimer();
			}
		}
	}
	,__stopCursorTimer: function() {
		if(this.__cursorTimer != null) {
			this.__cursorTimer.stop();
			this.__cursorTimer = null;
		}
		if(this.__showCursor) {
			this.__showCursor = false;
			this.__dirty = true;
		}
	}
	,__stopTextInput: function() {
		if(this.__inputEnabled && this.stage != null) {
			this.stage.window.backend.setEnableTextEvents(false);
			this.stage.window.onTextInput.remove($bind(this,this.window_onTextInput));
			this.stage.window.onKeyDown.remove($bind(this,this.window_onKeyDown));
			this.__inputEnabled = false;
			this.__stopCursorTimer();
		}
	}
	,__updateLayout: function() {
		if(this.__layoutDirty) {
			this.__textEngine.update();
			if(this.__textEngine.autoSize != openfl_text_TextFieldAutoSize.NONE) {
				var cacheWidth = this.__textEngine.width;
				var cacheHeight = this.__textEngine.height;
				var _g = this.__textEngine.autoSize;
				switch(_g[1]) {
				case 1:case 3:case 0:
					if(!this.__textEngine.wordWrap) this.__textEngine.width = this.__textEngine.textWidth + 4;
					this.__textEngine.height = this.__textEngine.textHeight + 4;
					break;
				default:
				}
				if(this.__textEngine.width != cacheWidth) {
					var _g1 = this.__textEngine.autoSize;
					switch(_g1[1]) {
					case 3:
						var _g11 = this;
						_g11.set_x(_g11.get_x() + (cacheWidth - this.__textEngine.width));
						break;
					case 0:
						var _g12 = this;
						_g12.set_x(_g12.get_x() + (cacheWidth - this.__textEngine.width) / 2);
						break;
					default:
					}
				}
				this.__textEngine.getBounds();
			}
			this.__layoutDirty = false;
		}
	}
	,set_autoSize: function(value) {
		if(value != this.__textEngine.autoSize) {
			this.__dirty = true;
			this.__layoutDirty = true;
		}
		return this.__textEngine.autoSize = value;
	}
	,set_defaultTextFormat: function(value) {
		this.__textFormat.__merge(value);
		this.__layoutDirty = true;
		this.__dirty = true;
		return value;
	}
	,set_embedFonts: function(value) {
		return this.__textEngine.embedFonts = value;
	}
	,get_height: function() {
		this.__updateLayout();
		return this.__textEngine.height;
	}
	,set_height: function(value) {
		if(this.get_scaleY() != 1 || value != this.__textEngine.height) {
			if(!this.__transformDirty) {
				this.__transformDirty = true;
				openfl_display_DisplayObject.__worldTransformDirty++;
			}
			this.__dirty = true;
			this.__layoutDirty = true;
		}
		this.set_scaleY(1);
		return this.__textEngine.height = value;
	}
	,get_htmlText: function() {
		return this.__textEngine.text;
	}
	,set_htmlText: function(value) {
		if(!this.__isHTML || this.__textEngine.text != value) {
			this.__dirty = true;
			this.__layoutDirty = true;
		}
		this.__isHTML = true;
		if(this.__div == null) {
			value = new EReg("<br>","g").replace(value,"\n");
			value = new EReg("<br/>","g").replace(value,"\n");
			var segments = value.split("<font");
			if(segments.length == 1) {
				value = new EReg("<.*?>","g").replace(value,"");
				if(this.__textEngine.textFormatRanges.length > 1) this.__textEngine.textFormatRanges.splice(1,this.__textEngine.textFormatRanges.length - 1);
				var range = this.__textEngine.textFormatRanges[0];
				range.format = this.__textFormat;
				range.start = 0;
				range.end = value.length;
				return this.__textEngine.text = value;
			} else {
				this.__textEngine.textFormatRanges.splice(0,this.__textEngine.textFormatRanges.length);
				value = "";
				var _g = 0;
				while(_g < segments.length) {
					var segment = segments[_g];
					++_g;
					if(segment == "") continue;
					var closeFontIndex = segment.indexOf("</font>");
					if(closeFontIndex > -1) {
						var start = segment.indexOf(">") + 1;
						var end = closeFontIndex;
						var format = this.__textFormat.clone();
						var faceIndex = segment.indexOf("face=");
						var colorIndex = segment.indexOf("color=");
						var sizeIndex = segment.indexOf("size=");
						if(faceIndex > -1 && faceIndex < start) {
							var len = segment.indexOf("\"",faceIndex);
							format.font = HxOverrides.substr(segment,faceIndex + 6,len);
						}
						if(colorIndex > -1 && colorIndex < start) format.color = Std.parseInt("0x" + HxOverrides.substr(segment,colorIndex + 8,6));
						if(sizeIndex > -1 && sizeIndex < start) format.size = Std.parseInt((function($this) {
							var $r;
							var len1 = segment.indexOf("\"",sizeIndex);
							$r = HxOverrides.substr(segment,sizeIndex + 6,len1);
							return $r;
						}(this)));
						var sub = segment.substring(start,end);
						sub = new EReg("<.*?>","g").replace(sub,"");
						this.__textEngine.textFormatRanges.push(new openfl__$internal_text_TextFormatRange(format,value.length,value.length + sub.length));
						value += sub;
						if(closeFontIndex + 7 < segment.length) {
							sub = HxOverrides.substr(segment,closeFontIndex + 7,null);
							this.__textEngine.textFormatRanges.push(new openfl__$internal_text_TextFormatRange(this.__textFormat,value.length,value.length + sub.length));
							value += sub;
						}
					} else {
						this.__textEngine.textFormatRanges.push(new openfl__$internal_text_TextFormatRange(this.__textFormat,value.length,value.length + segment.length));
						value += segment;
					}
				}
			}
		}
		return this.__textEngine.text = value;
	}
	,set_maxChars: function(value) {
		if(value != this.__textEngine.maxChars) {
			this.__dirty = true;
			this.__layoutDirty = true;
		}
		return this.__textEngine.maxChars = value;
	}
	,set_multiline: function(value) {
		if(value != this.__textEngine.multiline) {
			this.__dirty = true;
			this.__layoutDirty = true;
		}
		return this.__textEngine.multiline = value;
	}
	,get_scrollH: function() {
		return this.__textEngine.scrollH;
	}
	,get_scrollV: function() {
		return this.__textEngine.scrollV;
	}
	,get_selectable: function() {
		return this.__textEngine.selectable;
	}
	,set_selectable: function(value) {
		if(value != this.__textEngine.selectable && this.get_type() == openfl_text_TextFieldType.INPUT) {
			if(this.stage != null && this.stage.get_focus() == this) this.__startTextInput(); else if(!value) this.__stopTextInput();
		}
		return this.__textEngine.selectable = value;
	}
	,get_text: function() {
		return this.__textEngine.text;
	}
	,set_text: function(value) {
		if(this.__isHTML || this.__textEngine.text != value) {
			this.__dirty = true;
			this.__layoutDirty = true;
		} else return value;
		if(this.__textEngine.textFormatRanges.length > 1) this.__textEngine.textFormatRanges.splice(1,this.__textEngine.textFormatRanges.length - 1);
		var range = this.__textEngine.textFormatRanges[0];
		range.format = this.__textFormat;
		range.start = 0;
		range.end = value.length;
		this.__isHTML = false;
		return this.__textEngine.text = value;
	}
	,get_textHeight: function() {
		this.__updateLayout();
		return this.__textEngine.textHeight;
	}
	,get_type: function() {
		return this.__textEngine.type;
	}
	,set_type: function(value) {
		if(value != this.__textEngine.type) {
			if(value == openfl_text_TextFieldType.INPUT) {
				this.addEventListener(openfl_events_FocusEvent.FOCUS_IN,$bind(this,this.this_onFocusIn));
				this.addEventListener(openfl_events_FocusEvent.FOCUS_OUT,$bind(this,this.this_onFocusOut));
				this.addEventListener(openfl_events_Event.ADDED_TO_STAGE,$bind(this,this.this_onAddedToStage));
				this.this_onFocusIn(null);
			} else {
				this.removeEventListener(openfl_events_FocusEvent.FOCUS_IN,$bind(this,this.this_onFocusIn));
				this.removeEventListener(openfl_events_FocusEvent.FOCUS_OUT,$bind(this,this.this_onFocusOut));
				this.removeEventListener(openfl_events_Event.ADDED_TO_STAGE,$bind(this,this.this_onAddedToStage));
				this.__stopTextInput();
			}
			this.__dirty = true;
		}
		return this.__textEngine.type = value;
	}
	,get_width: function() {
		this.__updateLayout();
		return this.__textEngine.width;
	}
	,set_width: function(value) {
		if(this.get_scaleX() != 1 || this.__textEngine.width != value) {
			if(!this.__transformDirty) {
				this.__transformDirty = true;
				openfl_display_DisplayObject.__worldTransformDirty++;
			}
			this.__dirty = true;
			this.__layoutDirty = true;
		}
		this.set_scaleX(1);
		return this.__textEngine.width = value;
	}
	,set_wordWrap: function(value) {
		if(value != this.__textEngine.wordWrap) {
			this.__dirty = true;
			this.__layoutDirty = true;
		}
		return this.__textEngine.wordWrap = value;
	}
	,stage_onMouseMove: function(event) {
		if(this.stage == null) return;
		if(this.__textEngine.selectable && this.__selectionIndex >= 0) {
			this.__updateLayout();
			var position = this.__getPosition(this.get_mouseX(),this.get_mouseY());
			if(position != this.__caretIndex) {
				this.__caretIndex = position;
				this.__dirty = true;
			}
		}
	}
	,stage_onMouseUp: function(event) {
		if(this.stage == null) return;
		this.stage.removeEventListener(openfl_events_MouseEvent.MOUSE_MOVE,$bind(this,this.stage_onMouseMove));
		this.stage.removeEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.stage_onMouseUp));
		if(this.stage.get_focus() == this) {
			this.__getWorldTransform();
			this.__updateLayout();
			var px = this.__worldTransform.__transformInverseX(this.get_x(),this.get_y());
			var py = this.__worldTransform.__transformInverseY(this.get_x(),this.get_y());
			var upPos = this.__getPosition(this.get_mouseX(),this.get_mouseY());
			var leftPos;
			var rightPos;
			leftPos = Std["int"](Math.min(this.__selectionIndex,upPos));
			rightPos = Std["int"](Math.max(this.__selectionIndex,upPos));
			this.__selectionIndex = leftPos;
			this.__caretIndex = rightPos;
			if(this.__inputEnabled) {
				this.this_onFocusIn(null);
				this.__stopCursorTimer();
				this.__startCursorTimer();
			}
		}
	}
	,this_onAddedToStage: function(event) {
		this.this_onFocusIn(null);
	}
	,this_onFocusIn: function(event) {
		if(this.get_selectable() && this.get_type() == openfl_text_TextFieldType.INPUT && this.stage != null && this.stage.get_focus() == this) this.__startTextInput();
	}
	,this_onFocusOut: function(event) {
		this.__stopTextInput();
	}
	,this_onMouseDown: function(event) {
		if(!this.get_selectable()) return;
		this.__updateLayout();
		this.__caretIndex = this.__getPosition(this.get_mouseX(),this.get_mouseY());
		this.__selectionIndex = this.__caretIndex;
		this.__dirty = true;
		this.stage.addEventListener(openfl_events_MouseEvent.MOUSE_MOVE,$bind(this,this.stage_onMouseMove));
		this.stage.addEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.stage_onMouseUp));
	}
	,window_onKeyDown: function(key,modifier) {
		switch(key) {
		case 8:
			if(this.__selectionIndex == this.__caretIndex && this.__caretIndex > 0) this.__selectionIndex = this.__caretIndex - 1;
			if(this.__selectionIndex != this.__caretIndex) {
				this.replaceSelectedText("");
				this.__selectionIndex = this.__caretIndex;
				this.dispatchEvent(new openfl_events_Event(openfl_events_Event.CHANGE,true));
			}
			break;
		case 127:
			if(this.__selectionIndex == this.__caretIndex && this.__caretIndex < this.__textEngine.text.length) this.__selectionIndex = this.__caretIndex + 1;
			if(this.__selectionIndex != this.__caretIndex) {
				this.replaceSelectedText("");
				this.__selectionIndex = this.__caretIndex;
				this.dispatchEvent(new openfl_events_Event(openfl_events_Event.CHANGE,true));
			}
			break;
		case 1073741904:
			if(lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_shiftKey(modifier)) {
				if(this.__caretIndex > 0) this.__caretIndex--;
			} else {
				if(this.__selectionIndex == this.__caretIndex) {
					if(this.__caretIndex > 0) this.__caretIndex--;
				} else this.__caretIndex = Std["int"](Math.min(this.__caretIndex,this.__selectionIndex));
				this.__selectionIndex = this.__caretIndex;
			}
			this.__stopCursorTimer();
			this.__startCursorTimer();
			break;
		case 1073741903:
			if(lime_ui__$KeyModifier_KeyModifier_$Impl_$.get_shiftKey(modifier)) {
				if(this.__caretIndex < this.__textEngine.text.length) this.__caretIndex++;
			} else {
				if(this.__selectionIndex == this.__caretIndex) {
					if(this.__caretIndex < this.__textEngine.text.length) this.__caretIndex++;
				} else this.__caretIndex = Std["int"](Math.max(this.__caretIndex,this.__selectionIndex));
				this.__selectionIndex = this.__caretIndex;
			}
			this.__stopCursorTimer();
			this.__startCursorTimer();
			break;
		case 99:
			if(modifier == 64 || modifier == 128) lime_system_Clipboard.set_text(this.__textEngine.text.substring(this.__caretIndex,this.__selectionIndex));
			break;
		case 120:
			if(modifier == 64 || modifier == 128) {
				lime_system_Clipboard.set_text(this.__textEngine.text.substring(this.__caretIndex,this.__selectionIndex));
				if(this.__caretIndex != this.__selectionIndex) {
					this.replaceSelectedText("");
					this.dispatchEvent(new openfl_events_Event(openfl_events_Event.CHANGE,true));
				}
			}
			break;
		case 118:
			if(modifier == 64 || modifier == 128) {
				var text = lime_system_Clipboard.get_text();
				if(text != null) this.replaceSelectedText(text); else this.replaceSelectedText("");
				this.dispatchEvent(new openfl_events_Event(openfl_events_Event.CHANGE,true));
			}
			break;
		default:
		}
	}
	,window_onTextInput: function(value) {
		this.replaceSelectedText(value);
		this.dispatchEvent(new openfl_events_Event(openfl_events_Event.CHANGE,true));
	}
	,__class__: openfl_text_TextField
	,__properties__: $extend(openfl_display_InteractiveObject.prototype.__properties__,{get_textHeight:"get_textHeight",set_wordWrap:"set_wordWrap",set_type:"set_type",get_type:"get_type",set_text:"set_text",get_text:"get_text",set_selectable:"set_selectable",get_selectable:"get_selectable",get_scrollV:"get_scrollV",get_scrollH:"get_scrollH",set_multiline:"set_multiline",set_maxChars:"set_maxChars",set_htmlText:"set_htmlText",get_htmlText:"get_htmlText",set_embedFonts:"set_embedFonts",set_defaultTextFormat:"set_defaultTextFormat",set_autoSize:"set_autoSize"})
});
var openfl_text_TextFieldAutoSize = $hxClasses["openfl.text.TextFieldAutoSize"] = { __ename__ : true, __constructs__ : ["CENTER","LEFT","NONE","RIGHT"] };
openfl_text_TextFieldAutoSize.CENTER = ["CENTER",0];
openfl_text_TextFieldAutoSize.CENTER.toString = $estr;
openfl_text_TextFieldAutoSize.CENTER.__enum__ = openfl_text_TextFieldAutoSize;
openfl_text_TextFieldAutoSize.LEFT = ["LEFT",1];
openfl_text_TextFieldAutoSize.LEFT.toString = $estr;
openfl_text_TextFieldAutoSize.LEFT.__enum__ = openfl_text_TextFieldAutoSize;
openfl_text_TextFieldAutoSize.NONE = ["NONE",2];
openfl_text_TextFieldAutoSize.NONE.toString = $estr;
openfl_text_TextFieldAutoSize.NONE.__enum__ = openfl_text_TextFieldAutoSize;
openfl_text_TextFieldAutoSize.RIGHT = ["RIGHT",3];
openfl_text_TextFieldAutoSize.RIGHT.toString = $estr;
openfl_text_TextFieldAutoSize.RIGHT.__enum__ = openfl_text_TextFieldAutoSize;
var openfl_text_TextFieldType = $hxClasses["openfl.text.TextFieldType"] = { __ename__ : true, __constructs__ : ["DYNAMIC","INPUT"] };
openfl_text_TextFieldType.DYNAMIC = ["DYNAMIC",0];
openfl_text_TextFieldType.DYNAMIC.toString = $estr;
openfl_text_TextFieldType.DYNAMIC.__enum__ = openfl_text_TextFieldType;
openfl_text_TextFieldType.INPUT = ["INPUT",1];
openfl_text_TextFieldType.INPUT.toString = $estr;
openfl_text_TextFieldType.INPUT.__enum__ = openfl_text_TextFieldType;
var openfl_text_TextFormat = function(font,size,color,bold,italic,underline,url,target,align,leftMargin,rightMargin,indent,leading) {
	this.font = font;
	this.size = size;
	this.color = color;
	this.bold = bold;
	this.italic = italic;
	this.underline = underline;
	this.url = url;
	this.target = target;
	this.align = align;
	this.leftMargin = leftMargin;
	this.rightMargin = rightMargin;
	this.indent = indent;
	this.leading = leading;
};
$hxClasses["openfl.text.TextFormat"] = openfl_text_TextFormat;
openfl_text_TextFormat.__name__ = true;
openfl_text_TextFormat.prototype = {
	clone: function() {
		var newFormat = new openfl_text_TextFormat(this.font,this.size,this.color,this.bold,this.italic,this.underline,this.url,this.target);
		newFormat.align = this.align;
		newFormat.leftMargin = this.leftMargin;
		newFormat.rightMargin = this.rightMargin;
		newFormat.indent = this.indent;
		newFormat.leading = this.leading;
		newFormat.blockIndent = this.blockIndent;
		newFormat.bullet = this.bullet;
		newFormat.kerning = this.kerning;
		newFormat.letterSpacing = this.letterSpacing;
		newFormat.tabStops = this.tabStops;
		return newFormat;
	}
	,__merge: function(format) {
		if(format.font != null) this.font = format.font;
		if(format.size != null) this.size = format.size;
		if(format.color != null) this.color = format.color;
		if(format.bold != null) this.bold = format.bold;
		if(format.italic != null) this.italic = format.italic;
		if(format.underline != null) this.underline = format.underline;
		if(format.url != null) this.url = format.url;
		if(format.target != null) this.target = format.target;
		if(format.align != null) this.align = format.align;
		if(format.leftMargin != null) this.leftMargin = format.leftMargin;
		if(format.rightMargin != null) this.rightMargin = format.rightMargin;
		if(format.indent != null) this.indent = format.indent;
		if(format.leading != null) this.leading = format.leading;
		if(format.blockIndent != null) this.blockIndent = format.blockIndent;
		if(format.bullet != null) this.bullet = format.bullet;
		if(format.kerning != null) this.kerning = format.kerning;
		if(format.letterSpacing != null) this.letterSpacing = format.letterSpacing;
		if(format.tabStops != null) this.tabStops = format.tabStops;
	}
	,__class__: openfl_text_TextFormat
};
var openfl_text_TextFormatAlign = $hxClasses["openfl.text.TextFormatAlign"] = { __ename__ : true, __constructs__ : ["LEFT","RIGHT","JUSTIFY","CENTER"] };
openfl_text_TextFormatAlign.LEFT = ["LEFT",0];
openfl_text_TextFormatAlign.LEFT.toString = $estr;
openfl_text_TextFormatAlign.LEFT.__enum__ = openfl_text_TextFormatAlign;
openfl_text_TextFormatAlign.RIGHT = ["RIGHT",1];
openfl_text_TextFormatAlign.RIGHT.toString = $estr;
openfl_text_TextFormatAlign.RIGHT.__enum__ = openfl_text_TextFormatAlign;
openfl_text_TextFormatAlign.JUSTIFY = ["JUSTIFY",2];
openfl_text_TextFormatAlign.JUSTIFY.toString = $estr;
openfl_text_TextFormatAlign.JUSTIFY.__enum__ = openfl_text_TextFormatAlign;
openfl_text_TextFormatAlign.CENTER = ["CENTER",3];
openfl_text_TextFormatAlign.CENTER.toString = $estr;
openfl_text_TextFormatAlign.CENTER.__enum__ = openfl_text_TextFormatAlign;
var openfl_ui_GameInput = function() { };
$hxClasses["openfl.ui.GameInput"] = openfl_ui_GameInput;
openfl_ui_GameInput.__name__ = true;
openfl_ui_GameInput.__getDevice = function(gamepad) {
	if(gamepad == null) return null;
	if(!(openfl_ui_GameInput.__devices.h.__keys__[gamepad.__id__] != null)) {
		var device = new openfl_ui_GameInputDevice(gamepad.id == null?"null":"" + gamepad.id,null);
		openfl_ui_GameInput.__devices.set(gamepad,device);
		openfl_ui_GameInput.numDevices = Lambda.count(openfl_ui_GameInput.__devices);
	}
	return openfl_ui_GameInput.__devices.h[gamepad.__id__];
};
openfl_ui_GameInput.__onGamepadAxisMove = function(gamepad,axis,value) {
	var device = openfl_ui_GameInput.__getDevice(gamepad);
	if(device == null) return;
	if(device.enabled) {
		if(!device.__axis.h.hasOwnProperty(axis)) {
			var control1 = new openfl_ui_GameInputControl(device,"AXIS_" + (function($this) {
				var $r;
				switch(axis) {
				case 0:
					$r = "LEFT_X";
					break;
				case 1:
					$r = "LEFT_Y";
					break;
				case 2:
					$r = "RIGHT_X";
					break;
				case 3:
					$r = "RIGHT_Y";
					break;
				case 4:
					$r = "TRIGGER_LEFT";
					break;
				case 5:
					$r = "TRIGGER_RIGHT";
					break;
				default:
					$r = "UNKNOWN (" + axis + ")";
				}
				return $r;
			}(this)),-1,1);
			device.__axis.h[axis] = control1;
			device.__controls.push(control1);
		}
		var control = device.__axis.h[axis];
		control.value = value;
		control.dispatchEvent(new openfl_events_Event(openfl_events_Event.CHANGE));
	}
};
openfl_ui_GameInput.__onGamepadButtonDown = function(gamepad,button) {
	var device = openfl_ui_GameInput.__getDevice(gamepad);
	if(device == null) return;
	if(device.enabled) {
		if(!device.__button.h.hasOwnProperty(button)) {
			var control1 = new openfl_ui_GameInputControl(device,"BUTTON_" + (function($this) {
				var $r;
				switch(button) {
				case 0:
					$r = "A";
					break;
				case 1:
					$r = "B";
					break;
				case 2:
					$r = "X";
					break;
				case 3:
					$r = "Y";
					break;
				case 4:
					$r = "BACK";
					break;
				case 5:
					$r = "GUIDE";
					break;
				case 6:
					$r = "START";
					break;
				case 7:
					$r = "LEFT_STICK";
					break;
				case 8:
					$r = "RIGHT_STICK";
					break;
				case 9:
					$r = "LEFT_SHOULDER";
					break;
				case 10:
					$r = "RIGHT_SHOULDER";
					break;
				case 11:
					$r = "DPAD_UP";
					break;
				case 12:
					$r = "DPAD_DOWN";
					break;
				case 13:
					$r = "DPAD_LEFT";
					break;
				case 14:
					$r = "DPAD_RIGHT";
					break;
				default:
					$r = "UNKNOWN (" + button + ")";
				}
				return $r;
			}(this)),0,1);
			device.__button.h[button] = control1;
			device.__controls.push(control1);
		}
		var control = device.__button.h[button];
		control.value = 1;
		control.dispatchEvent(new openfl_events_Event(openfl_events_Event.CHANGE));
	}
};
openfl_ui_GameInput.__onGamepadButtonUp = function(gamepad,button) {
	var device = openfl_ui_GameInput.__getDevice(gamepad);
	if(device == null) return;
	if(device.enabled) {
		if(!device.__button.h.hasOwnProperty(button)) {
			var control1 = new openfl_ui_GameInputControl(device,"BUTTON_" + (function($this) {
				var $r;
				switch(button) {
				case 0:
					$r = "A";
					break;
				case 1:
					$r = "B";
					break;
				case 2:
					$r = "X";
					break;
				case 3:
					$r = "Y";
					break;
				case 4:
					$r = "BACK";
					break;
				case 5:
					$r = "GUIDE";
					break;
				case 6:
					$r = "START";
					break;
				case 7:
					$r = "LEFT_STICK";
					break;
				case 8:
					$r = "RIGHT_STICK";
					break;
				case 9:
					$r = "LEFT_SHOULDER";
					break;
				case 10:
					$r = "RIGHT_SHOULDER";
					break;
				case 11:
					$r = "DPAD_UP";
					break;
				case 12:
					$r = "DPAD_DOWN";
					break;
				case 13:
					$r = "DPAD_LEFT";
					break;
				case 14:
					$r = "DPAD_RIGHT";
					break;
				default:
					$r = "UNKNOWN (" + button + ")";
				}
				return $r;
			}(this)),0,1);
			device.__button.h[button] = control1;
			device.__controls.push(control1);
		}
		var control = device.__button.h[button];
		control.value = 0;
		control.dispatchEvent(new openfl_events_Event(openfl_events_Event.CHANGE));
	}
};
openfl_ui_GameInput.__onGamepadConnect = function(gamepad) {
	var device = openfl_ui_GameInput.__getDevice(gamepad);
	if(device == null) return;
	var _g = 0;
	var _g1 = openfl_ui_GameInput.__instances;
	while(_g < _g1.length) {
		var instance = _g1[_g];
		++_g;
		instance.dispatchEvent(new openfl_events_GameInputEvent(openfl_events_GameInputEvent.DEVICE_ADDED,null,null,device));
	}
};
openfl_ui_GameInput.__onGamepadDisconnect = function(gamepad) {
	var device = openfl_ui_GameInput.__devices.h[gamepad.__id__];
	if(device != null) {
		openfl_ui_GameInput.__devices.remove(gamepad);
		openfl_ui_GameInput.numDevices = Lambda.count(openfl_ui_GameInput.__devices);
		var _g = 0;
		var _g1 = openfl_ui_GameInput.__instances;
		while(_g < _g1.length) {
			var instance = _g1[_g];
			++_g;
			instance.dispatchEvent(new openfl_events_GameInputEvent(openfl_events_GameInputEvent.DEVICE_REMOVED,null,null,device));
		}
	}
};
openfl_ui_GameInput.__super__ = openfl_events_EventDispatcher;
openfl_ui_GameInput.prototype = $extend(openfl_events_EventDispatcher.prototype,{
	__class__: openfl_ui_GameInput
});
var openfl_ui_GameInputControl = function(device,id,minValue,maxValue,value) {
	if(value == null) value = 0;
	openfl_events_EventDispatcher.call(this);
	this.device = device;
	this.id = id;
	this.minValue = minValue;
	this.maxValue = maxValue;
	this.value = value;
};
$hxClasses["openfl.ui.GameInputControl"] = openfl_ui_GameInputControl;
openfl_ui_GameInputControl.__name__ = true;
openfl_ui_GameInputControl.__super__ = openfl_events_EventDispatcher;
openfl_ui_GameInputControl.prototype = $extend(openfl_events_EventDispatcher.prototype,{
	__class__: openfl_ui_GameInputControl
});
var openfl_ui_GameInputDevice = function(id,name) {
	this.__controls = [];
	this.__button = new haxe_ds_IntMap();
	this.__axis = new haxe_ds_IntMap();
	this.id = id;
	this.name = name;
	var control;
	var _g = 0;
	while(_g < 6) {
		var i = _g++;
		control = new openfl_ui_GameInputControl(this,"AXIS_" + i,-1,1);
		this.__axis.h[i] = control;
		this.__controls.push(control);
	}
	var _g1 = 0;
	while(_g1 < 15) {
		var i1 = _g1++;
		control = new openfl_ui_GameInputControl(this,"BUTTON_" + i1,0,1);
		this.__button.h[i1] = control;
		this.__controls.push(control);
	}
};
$hxClasses["openfl.ui.GameInputDevice"] = openfl_ui_GameInputDevice;
openfl_ui_GameInputDevice.__name__ = true;
openfl_ui_GameInputDevice.prototype = {
	__class__: openfl_ui_GameInputDevice
};
var openfl_ui_Keyboard = function() { };
$hxClasses["openfl.ui.Keyboard"] = openfl_ui_Keyboard;
openfl_ui_Keyboard.__name__ = true;
openfl_ui_Keyboard.__getCharCode = function(key,shift) {
	if(shift == null) shift = false;
	if(!shift) {
		switch(key) {
		case 8:
			return 8;
		case 9:
			return 9;
		case 13:
			return 13;
		case 27:
			return 27;
		case 32:
			return 32;
		case 186:
			return 59;
		case 187:
			return 61;
		case 188:
			return 44;
		case 189:
			return 45;
		case 190:
			return 46;
		case 191:
			return 47;
		case 192:
			return 96;
		case 219:
			return 91;
		case 220:
			return 92;
		case 221:
			return 93;
		case 222:
			return 39;
		}
		if(key >= 48 && key <= 57) return key - 48 + 48;
		if(key >= 65 && key <= 90) return key - 65 + 97;
	} else {
		switch(key) {
		case 48:
			return 41;
		case 49:
			return 33;
		case 50:
			return 64;
		case 51:
			return 35;
		case 52:
			return 36;
		case 53:
			return 37;
		case 54:
			return 94;
		case 55:
			return 38;
		case 56:
			return 42;
		case 57:
			return 40;
		case 186:
			return 58;
		case 187:
			return 43;
		case 188:
			return 60;
		case 189:
			return 95;
		case 190:
			return 62;
		case 191:
			return 63;
		case 192:
			return 126;
		case 219:
			return 123;
		case 220:
			return 124;
		case 221:
			return 125;
		case 222:
			return 34;
		}
		if(key >= 65 && key <= 90) return key - 65 + 65;
	}
	if(key >= 96 && key <= 105) return key - 96 + 48;
	switch(key) {
	case 106:
		return 42;
	case 107:
		return 43;
	case 108:
		return 44;
	case 110:
		return 45;
	case 111:
		return 46;
	case 46:
		return 127;
	case 13:
		return 13;
	case 8:
		return 8;
	}
	return 0;
};
var ru_octasoft_oem_designer_Equipment = function(grid) {
	this.offY = 0.;
	openfl_display_Sprite.call(this);
	this.items = [];
	this.grid = grid;
	this.body = new openfl_display_Sprite();
	this.addChild(this.body);
	this.scrollH = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
	this.scrollH -= 150;
	this.set_scrollRect(new openfl_geom_Rectangle(0,0,ru_octasoft_oem_designer_Main.RIGHTCOL_WIDTH,this.scrollH));
};
$hxClasses["ru.octasoft.oem.designer.Equipment"] = ru_octasoft_oem_designer_Equipment;
ru_octasoft_oem_designer_Equipment.__name__ = true;
ru_octasoft_oem_designer_Equipment.__super__ = openfl_display_Sprite;
ru_octasoft_oem_designer_Equipment.prototype = $extend(openfl_display_Sprite.prototype,{
	addObject: function(json,onLoaded) {
		var _g = this;
		ru_octasoft_oem_designer_HttpUtil.loadBitmap(json.imagePath,null,function(image) {
			var item = new ru_octasoft_oem_designer_Item(json.title,json.quantity,new ru_octasoft_oem_designer_figures_Figure(json.w,json.h,Type.createEnum(ru_octasoft_oem_designer_ItemType,json.type),json.id,image,_g.grid));
			item.set_y(_g.offY);
			_g.body.addChild(item);
			_g.offY += item.get_height();
			_g.offY += 15;
			_g.items.push(item);
			onLoaded();
		});
	}
	,scroll: function(k) {
		if(this.body.get_height() < this.scrollH) return;
		this.body.set_y((this.scrollH - this.body.get_height()) * k);
	}
	,findItemById: function(id) {
		var _g = 0;
		var _g1 = this.items;
		while(_g < _g1.length) {
			var i = _g1[_g];
			++_g;
			if(i.figure.id == id) return i;
		}
		return null;
	}
	,__class__: ru_octasoft_oem_designer_Equipment
});
var ru_octasoft_oem_designer_Grid = function() {
	this.scale = 1.;
	openfl_display_Sprite.call(this);
};
$hxClasses["ru.octasoft.oem.designer.Grid"] = ru_octasoft_oem_designer_Grid;
ru_octasoft_oem_designer_Grid.__name__ = true;
ru_octasoft_oem_designer_Grid.__super__ = openfl_display_Sprite;
ru_octasoft_oem_designer_Grid.prototype = $extend(openfl_display_Sprite.prototype,{
	init: function(w,h,gridType,clipSize) {
		this.w = w;
		this.h = h;
		var px = ru_octasoft_oem_designer_SizeUtil.unitsToPixels(1.);
		this.wPix = Math.round(w * px);
		this.hPix = Math.round(h * px);
		this.set_name("grid");
		this.gridType = gridType;
		this.buttonMode = true;
		this.useHandCursor = true;
		this.draw();
		var realScale;
		if(w > h) realScale = this.wPix; else realScale = this.hPix;
		var scale1 = 500. / realScale;
		this.setScale(scale1);
	}
	,draw: function() {
		this.scaledW = Math.round(this.wPix * this.scale);
		this.scaledH = Math.round(this.hPix * this.scale);
		this.get_graphics().clear();
		this.get_graphics().beginFill(0,0);
		this.get_graphics().drawRect(0,0,this.scaledW,this.scaledH);
		this.get_graphics().endFill();
		var c1 = 8355711;
		var c2 = 13421772;
		this.get_graphics().moveTo(-6,-3);
		var _g = this.gridType;
		switch(_g[1]) {
		case 0:
			this.get_graphics().lineStyle(6.,c1,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		case 1:
			this.get_graphics().lineStyle(6.,c1,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		case 2:
			this.get_graphics().lineStyle(6.,c1,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		case 3:
			this.get_graphics().lineStyle(6.,c2,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		}
		this.get_graphics().lineTo(this.scaledW + 6,-3);
		var _g1 = this.gridType;
		switch(_g1[1]) {
		case 0:
			this.get_graphics().lineStyle(6.,c1,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		case 1:
			this.get_graphics().lineStyle(6.,c1,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		case 2:
			this.get_graphics().lineStyle(6.,c2,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		case 3:
			this.get_graphics().lineStyle(6.,c2,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		}
		this.get_graphics().moveTo(this.scaledW + 3,-3);
		this.get_graphics().lineTo(this.scaledW + 3,this.scaledH + 6);
		this.get_graphics().lineStyle(6.,c2,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
		this.get_graphics().moveTo(this.scaledW,this.scaledH + 3);
		this.get_graphics().lineTo(0,this.scaledH + 3);
		var _g2 = this.gridType;
		switch(_g2[1]) {
		case 0:
			this.get_graphics().lineStyle(6.,c1,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		case 1:
			this.get_graphics().lineStyle(6.,c2,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		case 2:
			this.get_graphics().lineStyle(6.,c2,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		case 3:
			this.get_graphics().lineStyle(6.,c2,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
			break;
		}
		this.get_graphics().moveTo(-3,this.scaledH + 6);
		this.get_graphics().lineTo(-3,-3);
		var step = this.wPix / this.w * this.scale;
		var off = 0.;
		this.get_graphics().lineStyle(1.,8355711,null,null,openfl_display_LineScaleMode.NONE,openfl_display_CapsStyle.NONE,openfl_display_JointStyle.MITER);
		var _g11 = 0;
		var _g3 = this.h + 1;
		while(_g11 < _g3) {
			var c = _g11++;
			var r = Math.round(off);
			if(c != this.h && c != 0) {
				this.get_graphics().moveTo(0,r + 0.5);
				this.get_graphics().lineTo(this.scaledW,r + 0.5);
			}
			off += step;
		}
		off = 0.;
		var _g12 = 0;
		var _g4 = this.w + 1;
		while(_g12 < _g4) {
			var c3 = _g12++;
			var r1 = Math.round(off);
			if(c3 != this.w && c3 != 0) {
				this.get_graphics().moveTo(r1 + 0.5,0);
				this.get_graphics().lineTo(r1 + 0.5,this.scaledH);
			}
			off += step;
		}
	}
	,setScale: function(scale) {
		var _g1 = 0;
		var _g = this.get_numChildren();
		while(_g1 < _g) {
			var i = _g1++;
			var f = this.getChildAt(i);
			var _g2 = f;
			_g2.set_x(_g2.get_x() / this.scale);
			var _g21 = f;
			_g21.set_y(_g21.get_y() / this.scale);
		}
		this.scale = scale;
		var _g11 = 0;
		var _g3 = this.get_numChildren();
		while(_g11 < _g3) {
			var i1 = _g11++;
			var f1 = this.getChildAt(i1);
			var _g22 = f1;
			_g22.set_x(_g22.get_x() * this.scale);
			var _g23 = f1;
			_g23.set_y(_g23.get_y() * this.scale);
			f1.setScale(this.scale);
		}
		this.draw();
	}
	,drop: function(figure) {
		var editor = this;
		var localPoint = editor.globalToLocal(new openfl_geom_Point(figure.get_x(),figure.get_y()));
		localPoint.x = Math.round(localPoint.x);
		localPoint.y = Math.round(localPoint.y);
		figure.setScale(this.scale);
		this.dispatchEvent(new ru_octasoft_oem_designer_events_FigureAddedEvent(figure,localPoint,ru_octasoft_oem_designer_events_EventContext.DIRECT));
	}
	,add: function(item,x,y,rotation,lift) {
		if(lift == null) lift = 0;
		if(item.usedQty == item.qty) return;
		var figure = item.figure.copy();
		item.attach(figure);
		var localPoint = new openfl_geom_Point(ru_octasoft_oem_designer_SizeUtil.unitsToPixels(x) * this.scale,ru_octasoft_oem_designer_SizeUtil.unitsToPixels(y) * this.scale);
		figure.setScale(this.scale);
		figure.set_rotation(rotation);
		if(figure.id == "shelf") figure.set_lift(lift);
		this.dispatchEvent(new ru_octasoft_oem_designer_events_FigureAddedEvent(figure,localPoint,ru_octasoft_oem_designer_events_EventContext.DIRECT,true));
	}
	,zoomIn: function() {
		this.setScale(this.scale + 0.1);
	}
	,zoomOut: function() {
		this.setScale(this.scale - 0.1);
	}
	,__class__: ru_octasoft_oem_designer_Grid
});
var ru_octasoft_oem_designer_GridType = $hxClasses["ru.octasoft.oem.designer.GridType"] = { __ename__ : true, __constructs__ : ["row","corner","head","island"] };
ru_octasoft_oem_designer_GridType.row = ["row",0];
ru_octasoft_oem_designer_GridType.row.toString = $estr;
ru_octasoft_oem_designer_GridType.row.__enum__ = ru_octasoft_oem_designer_GridType;
ru_octasoft_oem_designer_GridType.corner = ["corner",1];
ru_octasoft_oem_designer_GridType.corner.toString = $estr;
ru_octasoft_oem_designer_GridType.corner.__enum__ = ru_octasoft_oem_designer_GridType;
ru_octasoft_oem_designer_GridType.head = ["head",2];
ru_octasoft_oem_designer_GridType.head.toString = $estr;
ru_octasoft_oem_designer_GridType.head.__enum__ = ru_octasoft_oem_designer_GridType;
ru_octasoft_oem_designer_GridType.island = ["island",3];
ru_octasoft_oem_designer_GridType.island.toString = $estr;
ru_octasoft_oem_designer_GridType.island.__enum__ = ru_octasoft_oem_designer_GridType;
var ru_octasoft_oem_designer_HttpUtil = function() { };
$hxClasses["ru.octasoft.oem.designer.HttpUtil"] = ru_octasoft_oem_designer_HttpUtil;
ru_octasoft_oem_designer_HttpUtil.__name__ = true;
ru_octasoft_oem_designer_HttpUtil.loadBitmap = function(url,vars,onComplete,onIOError) {
	var rq_status = -1;
	var loader = new openfl_net_URLLoader();
	var req = new openfl_net_URLRequest(url);
	loader.set_dataFormat(openfl_net_URLLoaderDataFormat.BINARY);
	loader.addEventListener(openfl_events_Event.COMPLETE,function(event) {
		var bytes;
		bytes = js_Boot.__cast(loader.data , lime_utils_ByteArray);
		openfl_display_BitmapData.fromBytes(bytes,null,onComplete);
	});
	loader.addEventListener(openfl_events_HTTPStatusEvent.HTTP_STATUS,function(event1) {
		rq_status = event1.status;
	});
	loader.addEventListener(openfl_events_IOErrorEvent.IO_ERROR,function(event2) {
		if(onIOError != null) onIOError(event2,rq_status);
	});
	loader.load(req);
};
var ru_octasoft_oem_designer_Item = function(title,qty,figure) {
	this.dragY = -1.;
	this.dragX = -1.;
	this.startDragY = -1.;
	this.startDragX = -1.;
	openfl_display_Sprite.call(this);
	this.title = title;
	this.qty = qty;
	this.figure = figure;
	var font = openfl_Assets.getFont(ru_octasoft_oem_designer_Main.FONT_NORMAL);
	var defaultFormat = new openfl_text_TextFormat(font.name,17,0);
	defaultFormat.align = openfl_text_TextFormatAlign.LEFT;
	var offY = 0.;
	var titleField = new openfl_text_TextField();
	titleField.set_selectable(false);
	titleField.set_defaultTextFormat(defaultFormat);
	titleField.set_embedFonts(true);
	titleField.set_autoSize(openfl_text_TextFieldAutoSize.LEFT);
	titleField.set_text(title);
	titleField.set_wordWrap(true);
	titleField.set_multiline(true);
	titleField.set_width(ru_octasoft_oem_designer_Main.RIGHTCOL_WIDTH - 1);
	this.addChild(titleField);
	offY = offY + titleField.get_textHeight() + 7;
	this.get_graphics().lineStyle(1,8355711);
	this.get_graphics().beginFill(0,0);
	this.get_graphics().drawRect(0.5,offY + 0.5,ru_octasoft_oem_designer_Main.RIGHTCOL_WIDTH - 1,ru_octasoft_oem_designer_Main.TOOLBAR_HEIGHT);
	this.get_graphics().endFill();
	var offX = 160;
	figure.set_x(offX / 2);
	figure.set_y(offY + ru_octasoft_oem_designer_Main.TOOLBAR_HEIGHT / 2);
	this.addChild(figure);
	defaultFormat.size = 13;
	this.left = new openfl_text_TextField();
	this.left.set_selectable(false);
	this.left.set_defaultTextFormat(defaultFormat);
	this.left.set_embedFonts(true);
	this.left.set_autoSize(openfl_text_TextFieldAutoSize.LEFT);
	this.left.set_y(offY + ru_octasoft_oem_designer_Main.TOOLBAR_HEIGHT / 2);
	this.left.set_x(offX);
	this.addChild(this.left);
	this.total = new openfl_text_TextField();
	this.total.set_selectable(false);
	this.total.set_defaultTextFormat(defaultFormat);
	this.total.set_embedFonts(true);
	this.total.set_autoSize(openfl_text_TextFieldAutoSize.LEFT);
	this.total.set_y(offY + ru_octasoft_oem_designer_Main.TOOLBAR_HEIGHT / 2 - 20);
	this.total.set_x(offX);
	this.total.set_text(ru_octasoft_oem_designer_Main.messages.ordered + ": " + qty);
	this.addChild(this.total);
	this.set_usedQty(0);
	this.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,$bind(this,this.mouseDown));
	this.useHandCursor = this.buttonMode = true;
};
$hxClasses["ru.octasoft.oem.designer.Item"] = ru_octasoft_oem_designer_Item;
ru_octasoft_oem_designer_Item.__name__ = true;
ru_octasoft_oem_designer_Item.__super__ = openfl_display_Sprite;
ru_octasoft_oem_designer_Item.prototype = $extend(openfl_display_Sprite.prototype,{
	updateQty: function() {
		this.left.set_text(ru_octasoft_oem_designer_Main.messages.placed + ": " + this.usedQty);
	}
	,attach: function(figure) {
		figure.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,$bind(this,this.mouseDown2));
	}
	,mouseDown: function(e) {
		if(this.usedQty == this.qty) return;
		var copy = this.figure.copy();
		var p = this.figure.localToGlobal(new openfl_geom_Point(0,0));
		copy.set_x(e.stageX);
		copy.set_y(e.stageY);
		this.stage.addChild(copy);
		copy.startDrag();
		copy.addEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.mouseUp));
		e.stopPropagation();
		this.startDragX = e.stageX;
		this.startDragY = e.stageY;
		this.dragging = copy;
	}
	,mouseDown2: function(e) {
		var t = e.target;
		t.parent.addChild(t);
		e.stopPropagation();
		var p = t.parent.localToGlobal(new openfl_geom_Point(t.get_x(),t.get_y()));
		this.startDragX = this.dragX = p.x;
		this.startDragY = this.dragY = p.y;
		this.stage.addEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.mouseUp));
		this.stage.addEventListener(openfl_events_MouseEvent.MOUSE_MOVE,$bind(this,this.onDrag));
		this.dragging = t;
	}
	,onDrag: function(e) {
		var dx = e.stageX - this.dragX;
		var dy = e.stageY - this.dragY;
		var _g = this.dragging;
		_g.set_x(_g.get_x() + dx);
		var _g1 = this.dragging;
		_g1.set_y(_g1.get_y() + dy);
		this.dragX = e.stageX;
		this.dragY = e.stageY;
		this.dragging.grid.dispatchEvent(new ru_octasoft_oem_designer_events_FigureDraggedEvent(this.dragging));
	}
	,mouseUp: function(e) {
		var figure = this.dragging;
		var hasClick = this.startDragX != -1 && this.startDragY != -1;
		if(!hasClick) return;
		var p = figure.parent.localToGlobal(new openfl_geom_Point(figure.get_x(),figure.get_y()));
		var hasDrag = hasClick && (this.startDragX != p.x || this.startDragY != p.y);
		if(figure.parent == this.stage) {
			figure.stopDrag();
			var point = new openfl_geom_Point(e.stageX,e.stageY);
			var objects = this.stage.getObjectsUnderPoint(point);
			if(objects.length > 0 && objects[0].get_name() == "grid") {
				figure.grid.drop(figure);
				figure.removeEventListener(openfl_events_MouseEvent.MOUSE_DOWN,$bind(this,this.mouseDown));
				figure.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,$bind(this,this.mouseDown2));
			} else this.stage.removeChild(figure);
		} else {
			this.stage.removeEventListener(openfl_events_MouseEvent.MOUSE_MOVE,$bind(this,this.onDrag));
			this.stage.removeEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.onDrag));
			if(hasDrag) {
				var localStartPoint = figure.grid.globalToLocal(new openfl_geom_Point(this.startDragX,this.startDragY));
				localStartPoint.x = Math.round(localStartPoint.x);
				localStartPoint.y = Math.round(localStartPoint.y);
				figure.grid.dispatchEvent(new ru_octasoft_oem_designer_events_FigureMovedEvent(figure,localStartPoint,new openfl_geom_Point(figure.get_x(),figure.get_y()),ru_octasoft_oem_designer_events_EventContext.DIRECT));
			} else if(hasClick) figure.grid.dispatchEvent(new ru_octasoft_oem_designer_events_FigureSelectedEvent(figure));
		}
		e.stopPropagation();
		this.startDragX = this.startDragY = -1.;
	}
	,set_usedQty: function(usedQty) {
		this.usedQty = usedQty;
		this.updateQty();
		return usedQty;
	}
	,__class__: ru_octasoft_oem_designer_Item
	,__properties__: $extend(openfl_display_Sprite.prototype.__properties__,{set_usedQty:"set_usedQty"})
});
var ru_octasoft_oem_designer_ItemType = $hxClasses["ru.octasoft.oem.designer.ItemType"] = { __ename__ : true, __constructs__ : ["simple","light","droppable","shelf","plug"] };
ru_octasoft_oem_designer_ItemType.simple = ["simple",0];
ru_octasoft_oem_designer_ItemType.simple.toString = $estr;
ru_octasoft_oem_designer_ItemType.simple.__enum__ = ru_octasoft_oem_designer_ItemType;
ru_octasoft_oem_designer_ItemType.light = ["light",1];
ru_octasoft_oem_designer_ItemType.light.toString = $estr;
ru_octasoft_oem_designer_ItemType.light.__enum__ = ru_octasoft_oem_designer_ItemType;
ru_octasoft_oem_designer_ItemType.droppable = ["droppable",2];
ru_octasoft_oem_designer_ItemType.droppable.toString = $estr;
ru_octasoft_oem_designer_ItemType.droppable.__enum__ = ru_octasoft_oem_designer_ItemType;
ru_octasoft_oem_designer_ItemType.shelf = ["shelf",3];
ru_octasoft_oem_designer_ItemType.shelf.toString = $estr;
ru_octasoft_oem_designer_ItemType.shelf.__enum__ = ru_octasoft_oem_designer_ItemType;
ru_octasoft_oem_designer_ItemType.plug = ["plug",4];
ru_octasoft_oem_designer_ItemType.plug.toString = $estr;
ru_octasoft_oem_designer_ItemType.plug.__enum__ = ru_octasoft_oem_designer_ItemType;
var ru_octasoft_oem_designer_Messages = function(language) {
	if(language) {
		this.rightColumnLabel = language.rightColumnLabel;
		this.ordered = language.ordered;
		this.placed = language.placed;
		this.shelfPopupLabel = language.shelfPopupLabel;
	} else {
		this.rightColumnLabel = "Equipments";
		this.ordered = "Ordered";
		this.placed = "Placed";
		this.shelfPopupLabel = "Please, set height (cm) for the shelf to be mounted. Switch shelves by pressing arrow buttons on your keyboard.";
	}
};
$hxClasses["ru.octasoft.oem.designer.Messages"] = ru_octasoft_oem_designer_Messages;
ru_octasoft_oem_designer_Messages.__name__ = true;
ru_octasoft_oem_designer_Messages.prototype = {
	__class__: ru_octasoft_oem_designer_Messages
};
var ru_octasoft_oem_designer_ShelfPopup = function() {
	this.offY = 0.;
	this.offX = 0.;
	var _g = this;
	openfl_display_Sprite.call(this);
	this.bitmap = new openfl_display_Bitmap(openfl_Assets.getBitmapData("assets/images/shelf_bg1.png"));
	this.addChild(this.bitmap);
	var font = openfl_Assets.getFont(ru_octasoft_oem_designer_Main.FONT_NORMAL);
	var defaultFormat = new openfl_text_TextFormat(font.name,13,0);
	defaultFormat.align = openfl_text_TextFormatAlign.LEFT;
	var font2 = openfl_Assets.getFont(ru_octasoft_oem_designer_Main.FONT_BOLD);
	var defaultFormat2 = new openfl_text_TextFormat(font2.name,14,0);
	defaultFormat2.align = openfl_text_TextFormatAlign.LEFT;
	var defaultFormat3 = new openfl_text_TextFormat(font2.name,11,16777215);
	defaultFormat3.align = openfl_text_TextFormatAlign.LEFT;
	var defaultFormat31 = new openfl_text_TextFormat(font2.name,11,16777215);
	defaultFormat31.align = openfl_text_TextFormatAlign.LEFT;
	var defaultFormat4 = new openfl_text_TextFormat(font.name,13,13421772);
	defaultFormat4.align = openfl_text_TextFormatAlign.LEFT;
	this.label = new openfl_text_TextField();
	this.label.set_selectable(false);
	this.label.set_text(ru_octasoft_oem_designer_Main.messages.shelfPopupLabel);
	this.label.set_defaultTextFormat(defaultFormat);
	this.label.set_embedFonts(true);
	this.label.set_wordWrap(true);
	this.label.set_autoSize(openfl_text_TextFieldAutoSize.LEFT);
	this.label.set_width(280);
	this.addChild(this.label);
	this.num = new openfl_text_TextField();
	this.num.set_selectable(false);
	this.num.set_defaultTextFormat(defaultFormat2);
	this.num.set_embedFonts(true);
	this.num.set_autoSize(openfl_text_TextFieldAutoSize.LEFT);
	this.addChild(this.num);
	this.save = new openfl_display_Sprite();
	this.save.get_graphics().beginFill(8355711);
	this.save.get_graphics().drawRect(0,0,100,30);
	this.save.get_graphics().endFill();
	this.save.buttonMode = true;
	this.save.useHandCursor = true;
	this.addChild(this.save);
	this.save.addEventListener(openfl_events_MouseEvent.MOUSE_OVER,$bind(this,this.hoverIn));
	this.save.addEventListener(openfl_events_MouseEvent.MOUSE_OUT,$bind(this,this.hoverOut));
	this.save.addEventListener(openfl_events_MouseEvent.CLICK,function(_) {
		_g.figure.set_lift(Std.parseInt(_g.units.get_text()));
		_g.set_visible(false);
		openfl_Lib.current.stage.set_focus(openfl_Lib.current.stage);
	});
	var saveLabel = new openfl_text_TextField();
	saveLabel.set_selectable(false);
	saveLabel.set_text("SAVE");
	saveLabel.set_defaultTextFormat(defaultFormat31);
	saveLabel.set_embedFonts(true);
	saveLabel.set_autoSize(openfl_text_TextFieldAutoSize.LEFT);
	saveLabel.set_x(50 - saveLabel.get_width() / 2);
	saveLabel.set_y(15 - saveLabel.get_height() / 2);
	this.save.addChild(saveLabel);
	this.input = new openfl_display_Sprite();
	this.input.get_graphics().lineStyle(1,11316396);
	this.input.get_graphics().drawRect(0.5,0.5,105,29);
	this.addChild(this.input);
	this.units = new openfl_text_TextField();
	this.units.set_type(openfl_text_TextFieldType.INPUT);
	this.units.set_text("0");
	this.units.set_defaultTextFormat(defaultFormat);
	this.units.set_embedFonts(true);
	this.units.set_autoSize(openfl_text_TextFieldAutoSize.LEFT);
	this.units.set_x(7);
	this.units.set_y(5);
	this.units.set_maxChars(3);
	this.units.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,function(e) {
		e.stopPropagation();
	});
	this.units.addEventListener(openfl_events_KeyboardEvent.KEY_UP,function(e1) {
		e1.stopPropagation();
		if(e1.charCode == 13) {
			_g.figure.set_lift(Std.parseInt(_g.units.get_text()));
			_g.set_visible(false);
			openfl_Lib.current.stage.set_focus(openfl_Lib.current.stage);
		}
	});
	this.units.addEventListener(openfl_events_Event.CHANGE,function(e2) {
		var r = new EReg("\\D","i");
		if(_g.units.get_text() == "") _g.units.set_text("0");
		if(_g.units.get_text().length > 3) _g.units.set_text((function($this) {
			var $r;
			var _this = _g.units.get_text();
			$r = HxOverrides.substr(_this,0,3);
			return $r;
		}(this)));
		if(r.match(_g.units.get_text())) _g.units.set_text(r.replace(_g.units.get_text(),""));
		_g.cm.set_x(_g.units.get_x() + _g.units.get_width() + 2);
	});
	this.input.addChild(this.units);
	this.cm = new openfl_text_TextField();
	this.cm.set_selectable(false);
	this.cm.set_text("cm");
	this.cm.set_defaultTextFormat(defaultFormat4);
	this.cm.set_embedFonts(true);
	this.cm.set_autoSize(openfl_text_TextFieldAutoSize.LEFT);
	this.cm.set_y(this.units.get_y());
	this.cm.set_x(this.units.get_x() + this.units.get_width() + 2);
	this.input.addChild(this.cm);
	this.set_visible(false);
};
$hxClasses["ru.octasoft.oem.designer.ShelfPopup"] = ru_octasoft_oem_designer_ShelfPopup;
ru_octasoft_oem_designer_ShelfPopup.__name__ = true;
ru_octasoft_oem_designer_ShelfPopup.__super__ = openfl_display_Sprite;
ru_octasoft_oem_designer_ShelfPopup.prototype = $extend(openfl_display_Sprite.prototype,{
	hoverOut: function(e) {
		this.save.get_graphics().beginFill(8355711);
		this.save.get_graphics().drawRect(0,0,100,30);
		this.save.get_graphics().endFill();
	}
	,hoverIn: function(e) {
		this.save.get_graphics().beginFill(10066329);
		this.save.get_graphics().drawRect(0,0,100,30);
		this.save.get_graphics().endFill();
	}
	,updateLanguage: function() {
		this.label.set_text(ru_octasoft_oem_designer_Main.messages.shelfPopupLabel);
	}
	,update: function(f) {
		this.figure = f;
		this.num.set_text(f.number + ".");
		this.units.set_text(f.get_lift() + "");
		this.cm.set_x(this.units.get_x() + this.units.get_width() + 2);
		var grid = f.parent;
		var bounds = f.picture.getBounds(grid);
		var stageBounds = f.picture.getBounds(f.stage);
		var inpOffX = 50;
		var saveOffX = 180;
		if(ru_octasoft_oem_designer_Main.compare(f.get_x(),bounds.width / 2)) {
			this.bitmap.bitmapData = openfl_Assets.getBitmapData("assets/images/popup_left.png");
			var pos = this.parent.globalToLocal(new openfl_geom_Point(stageBounds.get_right() + 40,stageBounds.y));
			this.set_x(pos.x);
			this.set_y(pos.y - this.get_height() / 2 + bounds.height / 2);
			this.offX = 15;
			this.offY = 0;
			this.label.set_x(this.offX + 15);
			this.label.set_y(this.offY + 15);
			this.num.set_x(this.offX + 15);
			this.num.set_y(this.offY + 85);
			this.input.set_x(this.offX + inpOffX);
			this.input.set_y(this.offY + 82);
			this.save.set_x(this.offX + saveOffX);
			this.save.set_y(this.offY + 82);
		} else if(ru_octasoft_oem_designer_Main.compare(f.get_y(),bounds.height / 2)) {
			this.bitmap.bitmapData = openfl_Assets.getBitmapData("assets/images/popup_top.png");
			var pos1 = this.parent.globalToLocal(new openfl_geom_Point(stageBounds.x,stageBounds.get_bottom() + 40));
			this.set_x(pos1.x - this.get_width() / 2 + bounds.width / 2);
			this.set_y(pos1.y);
			this.offX = 0;
			this.offY = 10;
			this.label.set_x(this.offX + 15);
			this.label.set_y(this.offY + 15);
			this.num.set_x(this.offX + 15);
			this.num.set_y(this.offY + 85);
			this.input.set_x(this.offX + inpOffX);
			this.input.set_y(this.offY + 82);
			this.save.set_x(this.offX + saveOffX);
			this.save.set_y(this.offY + 82);
		} else if(ru_octasoft_oem_designer_Main.compare(f.get_x(),grid.scaledW - bounds.width / 2)) {
			this.bitmap.bitmapData = openfl_Assets.getBitmapData("assets/images/popup_right.png");
			var pos2 = this.parent.globalToLocal(new openfl_geom_Point(stageBounds.x - this.get_width() - 40,stageBounds.y));
			this.set_x(pos2.x);
			this.set_y(pos2.y - this.get_height() / 2 + bounds.height / 2);
			this.offX = 0;
			this.offY = 0;
			this.label.set_x(this.offX + 15);
			this.label.set_y(this.offY + 15);
			this.num.set_x(this.offX + 15);
			this.num.set_y(this.offY + 85);
			this.input.set_x(this.offX + inpOffX);
			this.input.set_y(this.offY + 82);
			this.save.set_x(this.offX + saveOffX);
			this.save.set_y(this.offY + 82);
		}
		if(this.get_x() < 15) this.set_x(15);
		if(this.get_y() < 15) this.set_y(15);
		if(this.get_x() + this.get_width() > ru_octasoft_oem_designer_Main.clipSize - 15) this.set_x(ru_octasoft_oem_designer_Main.clipSize - 15 - this.get_width());
		if(this.get_y() + this.get_height() > ru_octasoft_oem_designer_Main.clipSize - 15) this.set_y(ru_octasoft_oem_designer_Main.clipSize - 15 - this.get_height());
	}
	,__class__: ru_octasoft_oem_designer_ShelfPopup
});
var ru_octasoft_oem_designer_SizeUtil = function() { };
$hxClasses["ru.octasoft.oem.designer.SizeUtil"] = ru_octasoft_oem_designer_SizeUtil;
ru_octasoft_oem_designer_SizeUtil.__name__ = true;
ru_octasoft_oem_designer_SizeUtil.unitsToPixels = function(units) {
	return units * ru_octasoft_oem_designer_SizeUtil.UNIT;
};
ru_octasoft_oem_designer_SizeUtil.pixelsToUnits = function(px) {
	return Math.round(px / ru_octasoft_oem_designer_SizeUtil.UNIT * 100.) / 100.;
};
var ru_octasoft_oem_designer_Toolbar = function() {
	var _g = this;
	openfl_display_Sprite.call(this);
	this.get_graphics().beginFill(13421772);
	var w = ru_octasoft_oem_designer_Main.clipSize + 12;
	this.get_graphics().drawRect(0,0,w,ru_octasoft_oem_designer_Main.TOOLBAR_HEIGHT);
	this.get_graphics().endFill();
	var padding = 15.;
	var paddingLeft = 15.;
	var step = (w - 360 - paddingLeft * 2) / 5.;
	var leftX = paddingLeft;
	var but = this.addButton("assets/images/rotate.png",function(_) {
		_g.dispatchEvent(new ru_octasoft_oem_designer_events_ClearEvent());
	});
	but.set_x(leftX);
	but.set_y(padding);
	leftX += but.get_width();
	leftX += step;
	but = this.addButton("assets/images/delete.png",function(_1) {
		_g.dispatchEvent(new ru_octasoft_oem_designer_events_DeleteEvent());
	});
	but.set_x(leftX);
	but.set_y(padding);
	leftX += but.get_width();
	leftX += step;
	but = this.addButton("assets/images/undo.png",function(_2) {
		_g.dispatchEvent(new ru_octasoft_oem_designer_events_UndoEvent());
	});
	but.set_x(leftX);
	but.set_y(padding);
	leftX += but.get_width();
	leftX += step;
	but = this.addButton("assets/images/redo.png",function(_3) {
		_g.dispatchEvent(new ru_octasoft_oem_designer_events_RedoEvent());
	});
	but.set_x(leftX);
	but.set_y(padding);
	leftX += but.get_width();
	leftX += step;
	but = this.addButton("assets/images/zoomOut.png",function(_4) {
		_g.dispatchEvent(new ru_octasoft_oem_designer_events_ZoomEvent(ru_octasoft_oem_designer_events_ZoomEvent.ZOOM_OUT));
	});
	but.set_x(leftX);
	but.set_y(padding);
	leftX += but.get_width();
	leftX += step;
	but = this.addButton("assets/images/zoomIn.png",function(_5) {
		_g.dispatchEvent(new ru_octasoft_oem_designer_events_ZoomEvent(ru_octasoft_oem_designer_events_ZoomEvent.ZOOM_IN));
	});
	but.set_x(leftX);
	but.set_y(padding);
};
$hxClasses["ru.octasoft.oem.designer.Toolbar"] = ru_octasoft_oem_designer_Toolbar;
ru_octasoft_oem_designer_Toolbar.__name__ = true;
ru_octasoft_oem_designer_Toolbar.__super__ = openfl_display_Sprite;
ru_octasoft_oem_designer_Toolbar.prototype = $extend(openfl_display_Sprite.prototype,{
	hoverOut: function(s) {
		s.get_graphics().beginFill(8355711);
		s.get_graphics().drawRect(0,0,60,60);
		s.get_graphics().endFill();
	}
	,hoverIn: function(s) {
		s.get_graphics().beginFill(10066329);
		s.get_graphics().drawRect(0,0,60,60);
		s.get_graphics().endFill();
	}
	,addButton: function(img,f) {
		var _g = this;
		var rotate = new openfl_display_Sprite();
		var png = new openfl_display_Bitmap(openfl_Assets.getBitmapData(img));
		png.set_x(30 - png.get_width() / 2);
		png.set_y(30 - png.get_height() / 2);
		rotate.addChild(png);
		rotate.buttonMode = rotate.useHandCursor = true;
		rotate.addEventListener(openfl_events_MouseEvent.CLICK,f);
		rotate.addEventListener(openfl_events_MouseEvent.MOUSE_OVER,function(_) {
			_g.hoverIn(rotate);
		});
		rotate.addEventListener(openfl_events_MouseEvent.MOUSE_OUT,function(_1) {
			_g.hoverOut(rotate);
		});
		this.hoverOut(rotate);
		this.addChild(rotate);
		return rotate;
	}
	,__class__: ru_octasoft_oem_designer_Toolbar
});
var ru_octasoft_oem_designer_events_ClearEvent = function() {
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_ClearEvent.NAME);
};
$hxClasses["ru.octasoft.oem.designer.events.ClearEvent"] = ru_octasoft_oem_designer_events_ClearEvent;
ru_octasoft_oem_designer_events_ClearEvent.__name__ = true;
ru_octasoft_oem_designer_events_ClearEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_ClearEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_ClearEvent
});
var ru_octasoft_oem_designer_events_DeleteEvent = function() {
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_DeleteEvent.NAME);
};
$hxClasses["ru.octasoft.oem.designer.events.DeleteEvent"] = ru_octasoft_oem_designer_events_DeleteEvent;
ru_octasoft_oem_designer_events_DeleteEvent.__name__ = true;
ru_octasoft_oem_designer_events_DeleteEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_DeleteEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_DeleteEvent
});
var ru_octasoft_oem_designer_events_EventContext = $hxClasses["ru.octasoft.oem.designer.events.EventContext"] = { __ename__ : true, __constructs__ : ["DIRECT","UNDO","REDO"] };
ru_octasoft_oem_designer_events_EventContext.DIRECT = ["DIRECT",0];
ru_octasoft_oem_designer_events_EventContext.DIRECT.toString = $estr;
ru_octasoft_oem_designer_events_EventContext.DIRECT.__enum__ = ru_octasoft_oem_designer_events_EventContext;
ru_octasoft_oem_designer_events_EventContext.UNDO = ["UNDO",1];
ru_octasoft_oem_designer_events_EventContext.UNDO.toString = $estr;
ru_octasoft_oem_designer_events_EventContext.UNDO.__enum__ = ru_octasoft_oem_designer_events_EventContext;
ru_octasoft_oem_designer_events_EventContext.REDO = ["REDO",2];
ru_octasoft_oem_designer_events_EventContext.REDO.toString = $estr;
ru_octasoft_oem_designer_events_EventContext.REDO.__enum__ = ru_octasoft_oem_designer_events_EventContext;
var ru_octasoft_oem_designer_events_FigureAddedEvent = function(figure,to,context,shift) {
	if(shift == null) shift = false;
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_FigureAddedEvent.NAME);
	this.figure = figure;
	this.to = to;
	this.context = context;
	this.shift = shift;
};
$hxClasses["ru.octasoft.oem.designer.events.FigureAddedEvent"] = ru_octasoft_oem_designer_events_FigureAddedEvent;
ru_octasoft_oem_designer_events_FigureAddedEvent.__name__ = true;
ru_octasoft_oem_designer_events_FigureAddedEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_FigureAddedEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_FigureAddedEvent
});
var ru_octasoft_oem_designer_events_FigureDeletedEvent = function(figure,from,context) {
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_FigureDeletedEvent.NAME);
	this.figure = figure;
	this.from = from;
	this.context = context;
};
$hxClasses["ru.octasoft.oem.designer.events.FigureDeletedEvent"] = ru_octasoft_oem_designer_events_FigureDeletedEvent;
ru_octasoft_oem_designer_events_FigureDeletedEvent.__name__ = true;
ru_octasoft_oem_designer_events_FigureDeletedEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_FigureDeletedEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_FigureDeletedEvent
});
var ru_octasoft_oem_designer_events_FigureDraggedEvent = function(figure) {
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_FigureDraggedEvent.NAME);
	this.figure = figure;
};
$hxClasses["ru.octasoft.oem.designer.events.FigureDraggedEvent"] = ru_octasoft_oem_designer_events_FigureDraggedEvent;
ru_octasoft_oem_designer_events_FigureDraggedEvent.__name__ = true;
ru_octasoft_oem_designer_events_FigureDraggedEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_FigureDraggedEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_FigureDraggedEvent
});
var ru_octasoft_oem_designer_events_FigureMovedEvent = function(figure,from,to,context) {
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_FigureMovedEvent.NAME);
	this.figure = figure;
	this.from = from;
	this.to = to;
	this.context = context;
};
$hxClasses["ru.octasoft.oem.designer.events.FigureMovedEvent"] = ru_octasoft_oem_designer_events_FigureMovedEvent;
ru_octasoft_oem_designer_events_FigureMovedEvent.__name__ = true;
ru_octasoft_oem_designer_events_FigureMovedEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_FigureMovedEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_FigureMovedEvent
});
var ru_octasoft_oem_designer_events_FigureRotatedEvent = function(figure,from,to,context) {
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_FigureRotatedEvent.NAME);
	this.figure = figure;
	this.from = from;
	this.to = to;
	this.context = context;
};
$hxClasses["ru.octasoft.oem.designer.events.FigureRotatedEvent"] = ru_octasoft_oem_designer_events_FigureRotatedEvent;
ru_octasoft_oem_designer_events_FigureRotatedEvent.__name__ = true;
ru_octasoft_oem_designer_events_FigureRotatedEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_FigureRotatedEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_FigureRotatedEvent
});
var ru_octasoft_oem_designer_events_FigureSelectedEvent = function(figure) {
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_FigureSelectedEvent.NAME);
	this.figure = figure;
};
$hxClasses["ru.octasoft.oem.designer.events.FigureSelectedEvent"] = ru_octasoft_oem_designer_events_FigureSelectedEvent;
ru_octasoft_oem_designer_events_FigureSelectedEvent.__name__ = true;
ru_octasoft_oem_designer_events_FigureSelectedEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_FigureSelectedEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_FigureSelectedEvent
});
var ru_octasoft_oem_designer_events_RedoEvent = function() {
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_RedoEvent.NAME);
};
$hxClasses["ru.octasoft.oem.designer.events.RedoEvent"] = ru_octasoft_oem_designer_events_RedoEvent;
ru_octasoft_oem_designer_events_RedoEvent.__name__ = true;
ru_octasoft_oem_designer_events_RedoEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_RedoEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_RedoEvent
});
var ru_octasoft_oem_designer_events_UndoEvent = function() {
	openfl_events_Event.call(this,ru_octasoft_oem_designer_events_UndoEvent.NAME);
};
$hxClasses["ru.octasoft.oem.designer.events.UndoEvent"] = ru_octasoft_oem_designer_events_UndoEvent;
ru_octasoft_oem_designer_events_UndoEvent.__name__ = true;
ru_octasoft_oem_designer_events_UndoEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_UndoEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_UndoEvent
});
var ru_octasoft_oem_designer_events_ZoomEvent = function(type) {
	openfl_events_Event.call(this,type);
};
$hxClasses["ru.octasoft.oem.designer.events.ZoomEvent"] = ru_octasoft_oem_designer_events_ZoomEvent;
ru_octasoft_oem_designer_events_ZoomEvent.__name__ = true;
ru_octasoft_oem_designer_events_ZoomEvent.__super__ = openfl_events_Event;
ru_octasoft_oem_designer_events_ZoomEvent.prototype = $extend(openfl_events_Event.prototype,{
	__class__: ru_octasoft_oem_designer_events_ZoomEvent
});
var ru_octasoft_oem_designer_figures_Figure = function(w,h,type,id,image,grid) {
	this.liftVal = 0;
	this.scale = 1.;
	this.shiftY = 0;
	this.shiftX = 0;
	this.rotationStart = -1.;
	openfl_display_Sprite.call(this);
	this.grid = grid;
	this.w = w;
	this.h = h;
	this.type = type;
	this.wPix = ru_octasoft_oem_designer_SizeUtil.unitsToPixels(w);
	this.hPix = ru_octasoft_oem_designer_SizeUtil.unitsToPixels(h);
	this.id = id;
	this.image = image;
	var font = openfl_Assets.getFont(ru_octasoft_oem_designer_Main.FONT_NORMAL);
	var defaultFormat = new openfl_text_TextFormat(font.name,13,0);
	defaultFormat.align = openfl_text_TextFormatAlign.LEFT;
	this.label = new openfl_text_TextField();
	this.label.set_selectable(false);
	this.label.set_text("100 cm");
	this.label.set_defaultTextFormat(defaultFormat);
	this.label.set_embedFonts(true);
	this.label.set_wordWrap(false);
	this.label.set_autoSize(openfl_text_TextFieldAutoSize.LEFT);
	this.label.set_visible(type == ru_octasoft_oem_designer_ItemType.shelf);
	this.addChild(this.label);
	this.set_lift(0);
	this.picture = new openfl_display_Bitmap(image);
	this.picture.smoothing = true;
	if(image.width > 120) {
		var k = 120. / image.width;
		this.scale = k;
	}
	if(image.height > 60) {
		var k1 = 60. / image.height;
		this.scale = k1;
	}
	this.addChild(this.picture);
	this.cover = new openfl_display_Sprite();
	this.addChild(this.cover);
	this.cover.set_visible(false);
	this.cover.mouseEnabled = false;
	this.h1 = new openfl_display_Sprite();
	this.h1.get_graphics().lineStyle(1.,8355711);
	this.h1.get_graphics().beginFill(0,0);
	this.h1.get_graphics().drawRect(0.5,0.5,6,6);
	this.h1.get_graphics().endFill();
	this.cover.addChild(this.h1);
	this.h1.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,$bind(this,this.onDragHandle));
	this.h1.addEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.stopRotate));
	this.decor1 = new openfl_display_Bitmap(openfl_Assets.getBitmapData("assets/images/decor.png"));
	this.cover.addChild(this.decor1);
	this.h2 = new openfl_display_Sprite();
	this.h2.get_graphics().lineStyle(1.,8355711);
	this.h2.get_graphics().beginFill(0,0);
	this.h2.get_graphics().drawRect(0.5,0.5,6,6);
	this.h2.get_graphics().endFill();
	this.cover.addChild(this.h2);
	this.h2.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,$bind(this,this.onDragHandle));
	this.h2.addEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.stopRotate));
	this.decor2 = new openfl_display_Bitmap(openfl_Assets.getBitmapData("assets/images/decor.png"));
	this.decor2.smoothing = true;
	this.decor2.set_rotation(90);
	this.cover.addChild(this.decor2);
	this.h3 = new openfl_display_Sprite();
	this.h3.get_graphics().lineStyle(1.,8355711);
	this.h3.get_graphics().beginFill(0,0);
	this.h3.get_graphics().drawRect(0.5,0.5,6,6);
	this.h3.get_graphics().endFill();
	this.cover.addChild(this.h3);
	this.h3.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,$bind(this,this.onDragHandle));
	this.h3.addEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.stopRotate));
	this.decor3 = new openfl_display_Bitmap(openfl_Assets.getBitmapData("assets/images/decor.png"));
	this.decor3.smoothing = true;
	this.decor3.set_rotation(180);
	this.cover.addChild(this.decor3);
	this.h4 = new openfl_display_Sprite();
	this.h4.get_graphics().lineStyle(1.,8355711);
	this.h4.get_graphics().beginFill(0,0);
	this.h4.get_graphics().drawRect(0.5,0.5,6,6);
	this.h4.get_graphics().endFill();
	this.cover.addChild(this.h4);
	this.h4.addEventListener(openfl_events_MouseEvent.MOUSE_DOWN,$bind(this,this.onDragHandle));
	this.h4.addEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.stopRotate));
	this.decor4 = new openfl_display_Bitmap(openfl_Assets.getBitmapData("assets/images/decor.png"));
	this.decor4.smoothing = true;
	this.decor4.set_rotation(270);
	this.cover.addChild(this.decor4);
	this.draw();
	this.buttonMode = true;
	this.useHandCursor = true;
	if(type == ru_octasoft_oem_designer_ItemType.shelf) this.number = ru_octasoft_oem_designer_figures_Figure.seq++;
};
$hxClasses["ru.octasoft.oem.designer.figures.Figure"] = ru_octasoft_oem_designer_figures_Figure;
ru_octasoft_oem_designer_figures_Figure.__name__ = true;
ru_octasoft_oem_designer_figures_Figure.__super__ = openfl_display_Sprite;
ru_octasoft_oem_designer_figures_Figure.prototype = $extend(openfl_display_Sprite.prototype,{
	draw: function() {
		this.picture.set_scaleX(this.picture.set_scaleY(this.scale));
		this.picture.set_x(-this.picture.get_width() / 2.);
		this.picture.set_y(-this.picture.get_height() / 2.);
		var scaledWPix = this.wPix * this.scale;
		var scaledHPix = this.hPix * this.scale;
		this.shiftX = Math.round(scaledWPix / 2 + 15);
		if(this.shiftX < 27) this.shiftX = 27.;
		this.shiftY = Math.round(scaledHPix / 2 + 15);
		if(this.shiftY < 27) this.shiftY = 27.;
		this.cover.get_graphics().clear();
		this.cover.get_graphics().lineStyle(1.,8355711);
		this.cover.get_graphics().drawRect(-this.shiftX + 0.5,-this.shiftY + 0.5,this.shiftX * 2,this.shiftY * 2);
		this.cover.get_graphics().endFill();
		this.h1.set_x(-this.shiftX - 3);
		this.h1.set_y(-this.shiftY - 3);
		this.decor1.set_x(-this.shiftX + 5);
		this.decor1.set_y(-this.shiftY + 5);
		this.h2.set_x(this.shiftX - 3);
		this.h2.set_y(-this.shiftY - 3);
		this.decor2.set_x(this.shiftX - 5);
		this.decor2.set_y(-this.shiftY + 5);
		this.h3.set_x(this.shiftX - 3);
		this.h3.set_y(this.shiftY - 3);
		this.decor3.set_x(this.shiftX - 5);
		this.decor3.set_y(this.shiftY - 5);
		this.h4.set_x(-this.shiftX - 3);
		this.h4.set_y(this.shiftY - 3);
		this.decor4.set_x(-this.shiftX + 5);
		this.decor4.set_y(this.shiftY - 5);
		this.label.set_x(-this.label.get_width() / 2.);
		this.label.set_y(this.picture.get_height() / 2.);
	}
	,setScale: function(scale) {
		this.scale = scale;
		this.draw();
	}
	,onDragHandle: function(e) {
		this.activeHandle = e.target;
		this.stage.addEventListener(openfl_events_MouseEvent.MOUSE_MOVE,$bind(this,this.doRotate));
		this.stage.addEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.stopRotate));
		this.stage.addEventListener(openfl_events_MouseEvent.MOUSE_OUT,$bind(this,this.stopRotate));
		e.stopImmediatePropagation();
		this.rotationStart = this.get_rotation();
	}
	,doRotate: function(e) {
		var stageFigureCenter = this.localToGlobal(new openfl_geom_Point(0,0));
		var x1 = this.activeHandle.get_x() + 5;
		var y1 = this.activeHandle.get_y() + 5;
		var d1 = Math.atan2(y1,x1) * 180 / Math.PI;
		var x = e.stageX - stageFigureCenter.x;
		var y = e.stageY - stageFigureCenter.y;
		var radians = Math.atan2(y,x);
		var degrees = radians * 180 / Math.PI;
		degrees = Math.round(degrees - d1);
		degrees = Math.round(degrees / 10) * 10;
		this.set_rotation(degrees);
	}
	,stopRotate: function(e) {
		this.stage.removeEventListener(openfl_events_MouseEvent.MOUSE_MOVE,$bind(this,this.doRotate));
		this.stage.removeEventListener(openfl_events_MouseEvent.MOUSE_UP,$bind(this,this.stopRotate));
		this.stage.removeEventListener(openfl_events_MouseEvent.MOUSE_OUT,$bind(this,this.stopRotate));
		e.stopImmediatePropagation();
		if(this.rotationStart != -1 && this.get_rotation() != this.rotationStart) this.grid.dispatchEvent(new ru_octasoft_oem_designer_events_FigureRotatedEvent(this,this.rotationStart,this.get_rotation(),ru_octasoft_oem_designer_events_EventContext.DIRECT));
		this.rotationStart = -1.;
	}
	,copy: function() {
		return new ru_octasoft_oem_designer_figures_Figure(this.w,this.h,this.type,this.id,this.image,this.grid);
	}
	,get_selected: function() {
		return this.cover.get_visible();
	}
	,set_selected: function(val) {
		this.label.set_visible(!val && this.type == ru_octasoft_oem_designer_ItemType.shelf);
		return this.cover.set_visible(val);
	}
	,get_lift: function() {
		return this.liftVal;
	}
	,set_lift: function(val) {
		this.label.set_text(val + " cm");
		return this.liftVal = val;
	}
	,__class__: ru_octasoft_oem_designer_figures_Figure
	,__properties__: $extend(openfl_display_Sprite.prototype.__properties__,{set_lift:"set_lift",get_lift:"get_lift",set_selected:"set_selected",get_selected:"get_selected"})
});
var ru_octasoft_oem_designer_jpg_Writer = function(out) {
	this.YTable = [];
	this.UVTable = [];
	this.fdtbl_Y = [];
	this.fdtbl_UV = [];
	var _g = 0;
	while(_g < 64) {
		var i = _g++;
		this.YTable.push(0);
		this.UVTable.push(0);
		this.fdtbl_Y.push(0.0);
		this.fdtbl_UV.push(0.0);
	}
	this.bitcode = new haxe_ds_IntMap();
	this.category = new haxe_ds_IntMap();
	this.byteout = out;
	this.bytenew = 0;
	this.bytepos = 7;
	this.YDC_HT = new haxe_ds_IntMap();
	this.UVDC_HT = new haxe_ds_IntMap();
	this.YAC_HT = new haxe_ds_IntMap();
	this.UVAC_HT = new haxe_ds_IntMap();
	this.YDU = [];
	this.UDU = [];
	this.VDU = [];
	this.DU = [];
	var _g1 = 0;
	while(_g1 < 64) {
		var i1 = _g1++;
		this.YDU.push(0.0);
		this.UDU.push(0.0);
		this.VDU.push(0.0);
		this.DU.push(0.0);
	}
	this.initZigZag();
	this.initLuminance();
	this.initChrominance();
	this.initHuffmanTbl();
	this.initCategoryNumber();
};
$hxClasses["ru.octasoft.oem.designer.jpg.Writer"] = ru_octasoft_oem_designer_jpg_Writer;
ru_octasoft_oem_designer_jpg_Writer.__name__ = true;
ru_octasoft_oem_designer_jpg_Writer.prototype = {
	initZigZag: function() {
		this.ZigZag = [0,1,5,6,14,15,27,28,2,4,7,13,16,26,29,42,3,8,12,17,25,30,41,43,9,11,18,24,31,40,44,53,10,19,23,32,39,45,52,54,20,22,33,38,46,51,55,60,21,34,37,47,50,56,59,61,35,36,48,49,57,58,62,63];
	}
	,initQuantTables: function(sf) {
		var YQT = [16,11,10,16,24,40,51,61,12,12,14,19,26,58,60,55,14,13,16,24,40,57,69,56,14,17,22,29,51,87,80,62,18,22,37,56,68,109,103,77,24,35,55,64,81,104,113,92,49,64,78,87,103,121,120,101,72,92,95,98,112,100,103,99];
		var _g = 0;
		while(_g < 64) {
			var i = _g++;
			var t = Math.floor((YQT[i] * sf + 50) / 100);
			if(t < 1) t = 1; else if(t > 255) t = 255;
			this.YTable[this.ZigZag[i]] = t;
		}
		var UVQT = [17,18,24,47,99,99,99,99,18,21,26,66,99,99,99,99,24,26,56,99,99,99,99,99,47,66,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99];
		var _g1 = 0;
		while(_g1 < 64) {
			var j = _g1++;
			var u = Math.floor((UVQT[j] * sf + 50) / 100);
			if(u < 1) u = 1; else if(u > 255) u = 255;
			this.UVTable[this.ZigZag[j]] = u;
		}
		var aasf = [1.0,1.387039845,1.306562965,1.175875602,1.0,0.785694958,0.541196100,0.275899379];
		var k = 0;
		var _g2 = 0;
		while(_g2 < 8) {
			var row = _g2++;
			var _g11 = 0;
			while(_g11 < 8) {
				var col = _g11++;
				this.fdtbl_Y[k] = 1.0 / (this.YTable[this.ZigZag[k]] * aasf[row] * aasf[col] * 8.0);
				this.fdtbl_UV[k] = 1.0 / (this.UVTable[this.ZigZag[k]] * aasf[row] * aasf[col] * 8.0);
				k++;
			}
		}
	}
	,initLuminance: function() {
		this.std_dc_luminance_nrcodes = [0,0,1,5,1,1,1,1,1,1,0,0,0,0,0,0,0];
		this.std_dc_luminance_values = this.strIntsToBytes("0,1,2,3,4,5,6,7,8,9,10,11");
		this.std_ac_luminance_nrcodes = [0,0,2,1,3,3,2,4,3,5,5,4,4,0,0,1,125];
		this.std_ac_luminance_values = this.strIntsToBytes("0x01,0x02,0x03,0x00,0x04,0x11,0x05,0x12," + "0x21,0x31,0x41,0x06,0x13,0x51,0x61,0x07," + "0x22,0x71,0x14,0x32,0x81,0x91,0xa1,0x08," + "0x23,0x42,0xb1,0xc1,0x15,0x52,0xd1,0xf0," + "0x24,0x33,0x62,0x72,0x82,0x09,0x0a,0x16," + "0x17,0x18,0x19,0x1a,0x25,0x26,0x27,0x28," + "0x29,0x2a,0x34,0x35,0x36,0x37,0x38,0x39," + "0x3a,0x43,0x44,0x45,0x46,0x47,0x48,0x49," + "0x4a,0x53,0x54,0x55,0x56,0x57,0x58,0x59," + "0x5a,0x63,0x64,0x65,0x66,0x67,0x68,0x69," + "0x6a,0x73,0x74,0x75,0x76,0x77,0x78,0x79," + "0x7a,0x83,0x84,0x85,0x86,0x87,0x88,0x89," + "0x8a,0x92,0x93,0x94,0x95,0x96,0x97,0x98," + "0x99,0x9a,0xa2,0xa3,0xa4,0xa5,0xa6,0xa7," + "0xa8,0xa9,0xaa,0xb2,0xb3,0xb4,0xb5,0xb6," + "0xb7,0xb8,0xb9,0xba,0xc2,0xc3,0xc4,0xc5," + "0xc6,0xc7,0xc8,0xc9,0xca,0xd2,0xd3,0xd4," + "0xd5,0xd6,0xd7,0xd8,0xd9,0xda,0xe1,0xe2," + "0xe3,0xe4,0xe5,0xe6,0xe7,0xe8,0xe9,0xea," + "0xf1,0xf2,0xf3,0xf4,0xf5,0xf6,0xf7,0xf8," + "0xf9,0xfa");
	}
	,strIntsToBytes: function(s) {
		var len = s.length;
		var b = new haxe_io_BytesBuffer();
		var val = 0;
		var i = 0;
		var _g = 0;
		while(_g < len) {
			var j = _g++;
			if(s.charAt(j) == ",") {
				val = Std.parseInt(HxOverrides.substr(s,i,j - i));
				b.b.push(val);
				i = j + 1;
			}
		}
		if(i < len) {
			val = Std.parseInt(HxOverrides.substr(s,i,null));
			b.b.push(val);
		}
		return b.getBytes();
	}
	,initChrominance: function() {
		this.std_dc_chrominance_nrcodes = [0,0,3,1,1,1,1,1,1,1,1,1,0,0,0,0,0];
		this.std_dc_chrominance_values = this.strIntsToBytes("0,1,2,3,4,5,6,7,8,9,10,11");
		this.std_ac_chrominance_nrcodes = [0,0,2,1,2,4,4,3,4,7,5,4,4,0,1,2,119];
		this.std_ac_chrominance_values = this.strIntsToBytes("0x00,0x01,0x02,0x03,0x11,0x04,0x05,0x21," + "0x31,0x06,0x12,0x41,0x51,0x07,0x61,0x71," + "0x13,0x22,0x32,0x81,0x08,0x14,0x42,0x91," + "0xa1,0xb1,0xc1,0x09,0x23,0x33,0x52,0xf0," + "0x15,0x62,0x72,0xd1,0x0a,0x16,0x24,0x34," + "0xe1,0x25,0xf1,0x17,0x18,0x19,0x1a,0x26," + "0x27,0x28,0x29,0x2a,0x35,0x36,0x37,0x38," + "0x39,0x3a,0x43,0x44,0x45,0x46,0x47,0x48," + "0x49,0x4a,0x53,0x54,0x55,0x56,0x57,0x58," + "0x59,0x5a,0x63,0x64,0x65,0x66,0x67,0x68," + "0x69,0x6a,0x73,0x74,0x75,0x76,0x77,0x78," + "0x79,0x7a,0x82,0x83,0x84,0x85,0x86,0x87," + "0x88,0x89,0x8a,0x92,0x93,0x94,0x95,0x96," + "0x97,0x98,0x99,0x9a,0xa2,0xa3,0xa4,0xa5," + "0xa6,0xa7,0xa8,0xa9,0xaa,0xb2,0xb3,0xb4," + "0xb5,0xb6,0xb7,0xb8,0xb9,0xba,0xc2,0xc3," + "0xc4,0xc5,0xc6,0xc7,0xc8,0xc9,0xca,0xd2," + "0xd3,0xd4,0xd5,0xd6,0xd7,0xd8,0xd9,0xda," + "0xe2,0xe3,0xe4,0xe5,0xe6,0xe7,0xe8,0xe9," + "0xea,0xf2,0xf3,0xf4,0xf5,0xf6,0xf7,0xf8," + "0xf9,0xfa");
	}
	,initHuffmanTbl: function() {
		this.YDC_HT = this.computeHuffmanTbl(this.std_dc_luminance_nrcodes,this.std_dc_luminance_values);
		this.UVDC_HT = this.computeHuffmanTbl(this.std_dc_chrominance_nrcodes,this.std_dc_chrominance_values);
		this.YAC_HT = this.computeHuffmanTbl(this.std_ac_luminance_nrcodes,this.std_ac_luminance_values);
		this.UVAC_HT = this.computeHuffmanTbl(this.std_ac_chrominance_nrcodes,this.std_ac_chrominance_values);
	}
	,computeHuffmanTbl: function(nrcodes,std_table) {
		var codevalue = 0;
		var pos_in_table = 0;
		var HT = new haxe_ds_IntMap();
		var _g = 1;
		while(_g < 17) {
			var k = _g++;
			var end = nrcodes[k];
			var _g1 = 0;
			while(_g1 < end) {
				var j = _g1++;
				var idx = std_table.b[pos_in_table];
				var value = new ru_octasoft_oem_designer_jpg__$Writer_BitString(k,codevalue);
				HT.h[idx] = value;
				pos_in_table++;
				codevalue++;
			}
			codevalue *= 2;
		}
		return HT;
	}
	,initCategoryNumber: function() {
		var nrlower = 1;
		var nrupper = 2;
		var idx;
		var _g = 1;
		while(_g < 16) {
			var cat = _g++;
			var _g1 = nrlower;
			while(_g1 < nrupper) {
				var nr = _g1++;
				idx = 32767 + nr;
				this.category.h[idx] = cat;
				var value = new ru_octasoft_oem_designer_jpg__$Writer_BitString(cat,nr);
				this.bitcode.h[idx] = value;
			}
			var nrneg = -(nrupper - 1);
			while(nrneg <= -nrlower) {
				idx = 32767 + nrneg;
				this.category.h[idx] = cat;
				var value1 = new ru_octasoft_oem_designer_jpg__$Writer_BitString(cat,nrupper - 1 + nrneg);
				this.bitcode.h[idx] = value1;
				nrneg++;
			}
			nrlower <<= 1;
			nrupper <<= 1;
		}
	}
	,writeBits: function(bs) {
		if(bs == null) return;
		var value = bs.val;
		var posval = bs.len - 1;
		while(posval >= 0) {
			if((value & 1 << posval) != 0) this.bytenew |= 1 << this.bytepos;
			posval--;
			this.bytepos--;
			if(this.bytepos < 0) {
				if(this.bytenew == 255) {
					this.byteout.writeByte(255);
					this.byteout.writeByte(0);
				} else this.byteout.writeByte(this.bytenew);
				this.bytepos = 7;
				this.bytenew = 0;
			}
		}
	}
	,writeWord: function(val) {
		this.byteout.writeByte(val >> 8 & 255);
		this.byteout.writeByte(val & 255);
	}
	,fDCTQuant: function(data,fdtbl) {
		var dataOff = 0;
		var _g = 0;
		while(_g < 8) {
			var i = _g++;
			var tmp0 = data[dataOff] + data[dataOff + 7];
			var tmp7 = data[dataOff] - data[dataOff + 7];
			var tmp1 = data[dataOff + 1] + data[dataOff + 6];
			var tmp6 = data[dataOff + 1] - data[dataOff + 6];
			var tmp2 = data[dataOff + 2] + data[dataOff + 5];
			var tmp5 = data[dataOff + 2] - data[dataOff + 5];
			var tmp3 = data[dataOff + 3] + data[dataOff + 4];
			var tmp4 = data[dataOff + 3] - data[dataOff + 4];
			var tmp10 = tmp0 + tmp3;
			var tmp13 = tmp0 - tmp3;
			var tmp11 = tmp1 + tmp2;
			var tmp12 = tmp1 - tmp2;
			data[dataOff] = tmp10 + tmp11;
			data[dataOff + 4] = tmp10 - tmp11;
			var z1 = (tmp12 + tmp13) * 0.707106781;
			data[dataOff + 2] = tmp13 + z1;
			data[dataOff + 6] = tmp13 - z1;
			tmp10 = tmp4 + tmp5;
			tmp11 = tmp5 + tmp6;
			tmp12 = tmp6 + tmp7;
			var z5 = (tmp10 - tmp12) * 0.382683433;
			var z2 = 0.541196100 * tmp10 + z5;
			var z4 = 1.306562965 * tmp12 + z5;
			var z3 = tmp11 * 0.707106781;
			var z11 = tmp7 + z3;
			var z13 = tmp7 - z3;
			data[dataOff + 5] = z13 + z2;
			data[dataOff + 3] = z13 - z2;
			data[dataOff + 1] = z11 + z4;
			data[dataOff + 7] = z11 - z4;
			dataOff += 8;
		}
		dataOff = 0;
		var _g1 = 0;
		while(_g1 < 8) {
			var j = _g1++;
			var tmp0p2 = data[dataOff] + data[dataOff + 56];
			var tmp7p2 = data[dataOff] - data[dataOff + 56];
			var tmp1p2 = data[dataOff + 8] + data[dataOff + 48];
			var tmp6p2 = data[dataOff + 8] - data[dataOff + 48];
			var tmp2p2 = data[dataOff + 16] + data[dataOff + 40];
			var tmp5p2 = data[dataOff + 16] - data[dataOff + 40];
			var tmp3p2 = data[dataOff + 24] + data[dataOff + 32];
			var tmp4p2 = data[dataOff + 24] - data[dataOff + 32];
			var tmp10p2 = tmp0p2 + tmp3p2;
			var tmp13p2 = tmp0p2 - tmp3p2;
			var tmp11p2 = tmp1p2 + tmp2p2;
			var tmp12p2 = tmp1p2 - tmp2p2;
			data[dataOff] = tmp10p2 + tmp11p2;
			data[dataOff + 32] = tmp10p2 - tmp11p2;
			var z1p2 = (tmp12p2 + tmp13p2) * 0.707106781;
			data[dataOff + 16] = tmp13p2 + z1p2;
			data[dataOff + 48] = tmp13p2 - z1p2;
			tmp10p2 = tmp4p2 + tmp5p2;
			tmp11p2 = tmp5p2 + tmp6p2;
			tmp12p2 = tmp6p2 + tmp7p2;
			var z5p2 = (tmp10p2 - tmp12p2) * 0.382683433;
			var z2p2 = 0.541196100 * tmp10p2 + z5p2;
			var z4p2 = 1.306562965 * tmp12p2 + z5p2;
			var z3p2 = tmp11p2 * 0.707106781;
			var z11p2 = tmp7p2 + z3p2;
			var z13p2 = tmp7p2 - z3p2;
			data[dataOff + 40] = z13p2 + z2p2;
			data[dataOff + 24] = z13p2 - z2p2;
			data[dataOff + 8] = z11p2 + z4p2;
			data[dataOff + 56] = z11p2 - z4p2;
			dataOff++;
		}
		var _g2 = 0;
		while(_g2 < 64) {
			var k = _g2++;
			data[k] = Math.round(data[k] * fdtbl[k]);
		}
		return data;
	}
	,writeAPP0: function() {
		this.byteout.writeByte(255);
		this.byteout.writeByte(224);
		this.byteout.writeByte(0);
		this.byteout.writeByte(16);
		this.byteout.writeByte(74);
		this.byteout.writeByte(70);
		this.byteout.writeByte(73);
		this.byteout.writeByte(70);
		this.byteout.writeByte(0);
		this.byteout.writeByte(1);
		this.byteout.writeByte(1);
		this.byteout.writeByte(0);
		this.byteout.writeByte(0);
		this.byteout.writeByte(1);
		this.byteout.writeByte(0);
		this.byteout.writeByte(1);
		this.byteout.writeByte(0);
		this.byteout.writeByte(0);
	}
	,writeDQT: function() {
		this.byteout.writeByte(255);
		this.byteout.writeByte(219);
		this.byteout.writeByte(0);
		this.byteout.writeByte(132);
		this.byteout.writeByte(0);
		var _g = 0;
		while(_g < 64) {
			var j = _g++;
			this.byteout.writeByte(this.YTable[j]);
		}
		this.byteout.writeByte(1);
		var _g1 = 0;
		while(_g1 < 64) {
			var j1 = _g1++;
			this.byteout.writeByte(this.UVTable[j1]);
		}
	}
	,writeSOF0: function(width,height) {
		this.byteout.writeByte(255);
		this.byteout.writeByte(192);
		this.byteout.writeByte(0);
		this.byteout.writeByte(17);
		this.byteout.writeByte(8);
		this.byteout.writeByte(height >> 8 & 255);
		this.byteout.writeByte(height & 255);
		this.byteout.writeByte(width >> 8 & 255);
		this.byteout.writeByte(width & 255);
		this.byteout.writeByte(3);
		this.byteout.writeByte(1);
		this.byteout.writeByte(17);
		this.byteout.writeByte(0);
		this.byteout.writeByte(2);
		this.byteout.writeByte(17);
		this.byteout.writeByte(1);
		this.byteout.writeByte(3);
		this.byteout.writeByte(17);
		this.byteout.writeByte(1);
	}
	,writeDHT: function() {
		this.byteout.writeByte(255);
		this.byteout.writeByte(196);
		this.byteout.writeByte(1);
		this.byteout.writeByte(162);
		this.byteout.writeByte(0);
		var _g = 1;
		while(_g < 17) {
			var j = _g++;
			this.byteout.writeByte(this.std_dc_luminance_nrcodes[j]);
		}
		this.byteout.write(this.std_dc_luminance_values);
		this.byteout.writeByte(16);
		var _g1 = 1;
		while(_g1 < 17) {
			var j1 = _g1++;
			this.byteout.writeByte(this.std_ac_luminance_nrcodes[j1]);
		}
		this.byteout.write(this.std_ac_luminance_values);
		this.byteout.writeByte(1);
		var _g2 = 1;
		while(_g2 < 17) {
			var j2 = _g2++;
			this.byteout.writeByte(this.std_dc_chrominance_nrcodes[j2]);
		}
		this.byteout.write(this.std_dc_chrominance_values);
		this.byteout.writeByte(17);
		var _g3 = 1;
		while(_g3 < 17) {
			var j3 = _g3++;
			this.byteout.writeByte(this.std_ac_chrominance_nrcodes[j3]);
		}
		this.byteout.write(this.std_ac_chrominance_values);
	}
	,writeSOS: function() {
		this.byteout.writeByte(255);
		this.byteout.writeByte(218);
		this.byteout.writeByte(0);
		this.byteout.writeByte(12);
		this.byteout.writeByte(3);
		this.byteout.writeByte(1);
		this.byteout.writeByte(0);
		this.byteout.writeByte(2);
		this.byteout.writeByte(17);
		this.byteout.writeByte(3);
		this.byteout.writeByte(17);
		this.byteout.writeByte(0);
		this.byteout.writeByte(63);
		this.byteout.writeByte(0);
	}
	,processDU: function(CDU,fdtbl,DC,HTDC,HTAC) {
		var EOB = HTAC.h[0];
		var M16zeroes = HTAC.h[240];
		var DU_DCT = this.fDCTQuant(CDU,fdtbl);
		var _g = 0;
		while(_g < 64) {
			var i1 = _g++;
			this.DU[this.ZigZag[i1]] = DU_DCT[i1];
		}
		var idx;
		var Diff = this.DU[0] - DC | 0;
		DC = this.DU[0];
		if(Diff == 0) this.writeBits(HTDC.h[0]); else {
			idx = 32767 + Diff;
			this.writeBits((function($this) {
				var $r;
				var key = $this.category.h[idx];
				$r = HTDC.h[key];
				return $r;
			}(this)));
			this.writeBits(this.bitcode.h[idx]);
		}
		var end0pos = 63;
		while(end0pos > 0 && this.DU[end0pos] == 0.0) end0pos--;
		if(end0pos == 0) {
			this.writeBits(EOB);
			return DC;
		}
		var i = 1;
		while(i <= end0pos) {
			var startpos = i;
			while(this.DU[i] == 0.0 && i <= end0pos) i++;
			var nrzeroes = i - startpos;
			if(nrzeroes >= 16) {
				var _g1 = 0;
				var _g2 = nrzeroes >> 4;
				while(_g1 < _g2) {
					var nrmarker = _g1++;
					this.writeBits(M16zeroes);
				}
				nrzeroes &= 15;
			}
			idx = 32767 + (this.DU[i] | 0);
			this.writeBits((function($this) {
				var $r;
				var key1 = nrzeroes * 16 + $this.category.h[idx];
				$r = HTAC.h[key1];
				return $r;
			}(this)));
			this.writeBits(this.bitcode.h[idx]);
			i++;
		}
		if(end0pos != 63) this.writeBits(EOB);
		return DC;
	}
	,RGB2YUV: function(img,width,xpos,ypos) {
		var pos = 0;
		var _g = 0;
		while(_g < 8) {
			var y = _g++;
			var offset = (y + ypos) * width + xpos << 2;
			var _g1 = 0;
			while(_g1 < 8) {
				var x = _g1++;
				offset++;
				var R = img.get(offset++);
				var G = img.get(offset++);
				var B = img.get(offset++);
				this.YDU[pos] = 0.29900 * R + 0.58700 * G + 0.11400 * B - 128;
				this.UDU[pos] = -0.16874 * R + -0.33126 * G + 0.50000 * B;
				this.VDU[pos] = 0.50000 * R + -0.41869 * G + -0.08131 * B;
				pos++;
			}
		}
	}
	,write: function(image) {
		var quality = image.quality;
		if(quality <= 0) quality = 1;
		if(quality > 100) quality = 100;
		var sf;
		if(quality < 50) sf = 5000 / quality | 0; else sf = 200 - quality * 2 | 0;
		this.initQuantTables(sf);
		this.bytenew = 0;
		this.bytepos = 7;
		var width = image.width;
		var height = image.height;
		this.writeWord(65496);
		this.writeAPP0();
		this.writeDQT();
		this.writeSOF0(width,height);
		this.writeDHT();
		this.writeSOS();
		var DCY = 0.0;
		var DCU = 0.0;
		var DCV = 0.0;
		this.bytenew = 0;
		this.bytepos = 7;
		var ypos = 0;
		while(ypos < height) {
			var xpos = 0;
			while(xpos < width) {
				this.RGB2YUV(image.pixels,width,xpos,ypos);
				DCY = this.processDU(this.YDU,this.fdtbl_Y,DCY,this.YDC_HT,this.YAC_HT);
				DCU = this.processDU(this.UDU,this.fdtbl_UV,DCU,this.UVDC_HT,this.UVAC_HT);
				DCV = this.processDU(this.VDU,this.fdtbl_UV,DCV,this.UVDC_HT,this.UVAC_HT);
				xpos += 8;
			}
			ypos += 8;
		}
		if(this.bytepos >= 0) {
			var fillbits = new ru_octasoft_oem_designer_jpg__$Writer_BitString(this.bytepos + 1,(1 << this.bytepos + 1) - 1);
			this.writeBits(fillbits);
		}
		this.writeWord(65497);
	}
	,__class__: ru_octasoft_oem_designer_jpg_Writer
};
var ru_octasoft_oem_designer_jpg__$Writer_BitString = function(l,v) {
	this.len = l;
	this.val = v;
};
$hxClasses["ru.octasoft.oem.designer.jpg._Writer.BitString"] = ru_octasoft_oem_designer_jpg__$Writer_BitString;
ru_octasoft_oem_designer_jpg__$Writer_BitString.__name__ = true;
ru_octasoft_oem_designer_jpg__$Writer_BitString.prototype = {
	__class__: ru_octasoft_oem_designer_jpg__$Writer_BitString
};
function $iterator(o) { if( o instanceof Array ) return function() { return HxOverrides.iter(o); }; return typeof(o.iterator) == 'function' ? $bind(o,o.iterator) : o.iterator; }
var $_, $fid = 0;
function $bind(o,m) { if( m == null ) return null; if( m.__id__ == null ) m.__id__ = $fid++; var f; if( o.hx__closures__ == null ) o.hx__closures__ = {}; else f = o.hx__closures__[m.__id__]; if( f == null ) { f = function(){ return f.method.apply(f.scope, arguments); }; f.scope = o; f.method = m; o.hx__closures__[m.__id__] = f; } return f; }
if(Array.prototype.indexOf) HxOverrides.indexOf = function(a,o,i) {
	return Array.prototype.indexOf.call(a,o,i);
};
$hxClasses.Math = Math;
String.prototype.__class__ = $hxClasses.String = String;
String.__name__ = true;
$hxClasses.Array = Array;
Array.__name__ = true;
Date.prototype.__class__ = $hxClasses.Date = Date;
Date.__name__ = ["Date"];
var Int = $hxClasses.Int = { __name__ : ["Int"]};
var Dynamic = $hxClasses.Dynamic = { __name__ : ["Dynamic"]};
var Float = $hxClasses.Float = Number;
Float.__name__ = ["Float"];
var Bool = $hxClasses.Bool = Boolean;
Bool.__ename__ = ["Bool"];
var Class = $hxClasses.Class = { __name__ : ["Class"]};
var Enum = { };
var __map_reserved = {}
var ArrayBuffer = $global.ArrayBuffer || js_html_compat_ArrayBuffer;
if(ArrayBuffer.prototype.slice == null) ArrayBuffer.prototype.slice = js_html_compat_ArrayBuffer.sliceImpl;
var DataView = $global.DataView || js_html_compat_DataView;
var Uint8Array = $global.Uint8Array || js_html_compat_Uint8Array._new;
var this1;
this1 = new Uint32Array(256);
lime_math_color__$RGBA_RGBA_$Impl_$.__alpha16 = this1;
var _g = 0;
while(_g < 256) {
	var i = _g++;
	var val = Math.ceil(i * 257.00392156862745);
	lime_math_color__$RGBA_RGBA_$Impl_$.__alpha16[i] = val;
}
var this2;
this2 = new Uint8Array(510);
lime_math_color__$RGBA_RGBA_$Impl_$.__clamp = this2;
var _g1 = 0;
while(_g1 < 255) {
	var i1 = _g1++;
	lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[i1] = i1;
}
var _g11 = 255;
var _g2 = 511;
while(_g11 < _g2) {
	var i2 = _g11++;
	lime_math_color__$RGBA_RGBA_$Impl_$.__clamp[i2] = 255;
}
if(window.createjs != null) createjs.Sound.alternateExtensions = ["ogg","mp3","wav"];
openfl_display_DisplayObject.__instanceCount = 0;
openfl_display_DisplayObject.__worldRenderDirty = 0;
openfl_display_DisplayObject.__worldTransformDirty = 0;
openfl_display_DisplayObject.__cacheAsBitmapMode = false;
ru_octasoft_oem_designer_Main.FONT_NORMAL = "assets/fonts/gothaproreg-webfont.ttf";
ru_octasoft_oem_designer_Main.FONT_BOLD = "assets/fonts/gothaprobol-webfont.ttf";
ru_octasoft_oem_designer_Main.TOOLBAR_HEIGHT = 90;
ru_octasoft_oem_designer_Main.RIGHTCOL_WIDTH = 300;
ru_octasoft_oem_designer_Main.DEFAULT_GRID = 5;
ru_octasoft_oem_designer_Main.GRID_PADDING = 36;
ru_octasoft_oem_designer_Main.draggable = true;
ru_octasoft_oem_designer_Main.clipSize = 528.;
ru_octasoft_oem_designer_Main.click = new openfl_geom_Point();
ru_octasoft_oem_designer_Main.prev = 0.;
openfl_text_Font.__registeredFonts = [];
haxe_crypto_Base64.CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
haxe_crypto_Base64.BYTES = haxe_io_Bytes.ofString(haxe_crypto_Base64.CHARS);
haxe_ds_ObjectMap.count = 0;
haxe_io_FPHelper.i64tmp = (function($this) {
	var $r;
	var x = new haxe__$Int64__$_$_$Int64(0,0);
	$r = x;
	return $r;
}(this));
js_Boot.__toStr = {}.toString;
js_html_compat_Uint8Array.BYTES_PER_ELEMENT = 1;
lime_Assets.cache = new lime_AssetCache();
lime_Assets.libraries = new haxe_ds_StringMap();
lime_Assets.onChange = new lime_app_Event_$Void_$Void();
lime_Assets.initialized = false;
lime__$backend_html5_HTML5Window.windowID = 0;
lime_app_Preloader.images = new haxe_ds_StringMap();
lime_app_Preloader.loaders = new haxe_ds_StringMap();
lime_graphics_Image.__base64Chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
lime_ui_Gamepad.onConnect = new lime_app_Event_$lime_$ui_$Gamepad_$Void();
lime_ui_Joystick.onConnect = new lime_app_Event_$lime_$ui_$Joystick_$Void();
lime_ui_Touch.onEnd = new lime_app_Event_$lime_$ui_$Touch_$Void();
lime_ui_Touch.onMove = new lime_app_Event_$lime_$ui_$Touch_$Void();
lime_ui_Touch.onStart = new lime_app_Event_$lime_$ui_$Touch_$Void();
motion_actuators_SimpleActuator.actuators = [];
motion_actuators_SimpleActuator.actuatorsLength = 0;
motion_actuators_SimpleActuator.addedEvent = false;
motion_Actuate.defaultActuator = motion_actuators_SimpleActuator;
motion_Actuate.defaultEase = motion_easing_Expo.get_easeOut();
motion_Actuate.targetLibraries = new haxe_ds_ObjectMap();
openfl_Assets.cache = new openfl_AssetCache();
openfl_display_LoaderInfo.__rootURL = window.document.URL;
openfl_system_ApplicationDomain.currentDomain = new openfl_system_ApplicationDomain(null);
openfl_geom_Matrix.__temp = new openfl_geom_Matrix();
openfl_geom_Matrix.__identity = new openfl_geom_Matrix();
openfl_Lib.current = new openfl_display_MovieClip();
openfl__$internal_renderer_GraphicsPaths.SIN45 = 0.70710678118654752440084436210485;
openfl__$internal_renderer_GraphicsPaths.TAN22 = 0.4142135623730950488016887242097;
openfl__$internal_renderer_cairo_CairoGraphics.SIN45 = 0.70710678118654752440084436210485;
openfl__$internal_renderer_cairo_CairoGraphics.TAN22 = 0.4142135623730950488016887242097;
openfl__$internal_renderer_canvas_CanvasGraphics.SIN45 = 0.70710678118654752440084436210485;
openfl__$internal_renderer_canvas_CanvasGraphics.TAN22 = 0.4142135623730950488016887242097;
openfl__$internal_renderer_canvas_CanvasGraphics.fillCommands = new openfl__$internal_renderer_DrawCommandBuffer();
openfl__$internal_renderer_canvas_CanvasGraphics.strokeCommands = new openfl__$internal_renderer_DrawCommandBuffer();
openfl__$internal_renderer_opengl_GLBitmap.fbData = [];
openfl__$internal_renderer_opengl_GLRenderer.glContextId = 0;
openfl__$internal_renderer_opengl_GLRenderer.glContexts = [];
openfl__$internal_renderer_opengl_shaders2_Shader.UID = 0;
openfl__$internal_renderer_opengl_shaders2_DefaultShader.VERTEX_SRC = ["attribute vec2 " + "openfl_aPosition" + ";","attribute vec2 " + "openfl_aTexCoord0" + ";","attribute vec4 " + "openfl_aColor" + ";","uniform mat3 " + "openfl_uProjectionMatrix" + ";","uniform bool " + "openfl_uUseColorTransform" + ";","varying vec2 " + "openfl_vTexCoord" + ";","varying vec4 " + "openfl_vColor" + ";","void main(void) {","   gl_Position = vec4((" + "openfl_uProjectionMatrix" + " * vec3(" + "openfl_aPosition" + ", 1.0)).xy, 0.0, 1.0);","   " + "openfl_vTexCoord" + " = " + "openfl_aTexCoord0" + ";","   if(" + "openfl_uUseColorTransform" + ")","   \t" + "openfl_vColor" + " = " + "openfl_aColor" + ";","   else","   \t" + "openfl_vColor" + " = vec4(" + "openfl_aColor" + ".rgb * " + "openfl_aColor" + ".a, " + "openfl_aColor" + ".a);","}"];
openfl__$internal_renderer_opengl_utils_PathBuiler.__currentWinding = 0;
openfl__$internal_renderer_opengl_utils_PathBuiler.__fillIndex = 0;
openfl_geom_Rectangle.__temp = new openfl_geom_Rectangle();
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.fillVertexAttributes = [new openfl__$internal_renderer_opengl_utils_VertexAttribute(2,5126,false,"openfl_aPosition")];
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.drawTrianglesVertexAttributes = [new openfl__$internal_renderer_opengl_utils_VertexAttribute(2,5126,false,"openfl_aPosition"),new openfl__$internal_renderer_opengl_utils_VertexAttribute(2,5126,false,"openfl_aTexCoord0"),new openfl__$internal_renderer_opengl_utils_VertexAttribute(4,5121,true,"openfl_aColor")];
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.primitiveVertexAttributes = [new openfl__$internal_renderer_opengl_utils_VertexAttribute(2,5126,false,"openfl_aPosition"),new openfl__$internal_renderer_opengl_utils_VertexAttribute(4,5126,false,"openfl_aColor")];
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.bucketPool = [];
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectPosition = new openfl_geom_Point();
openfl__$internal_renderer_opengl_utils_GraphicsRenderer.objectBounds = new openfl_geom_Rectangle();
openfl__$internal_renderer_opengl_utils_ShaderManager.compiledShadersCache = new haxe_ds_StringMap();
openfl_display_Shader.uObjectSize = "openfl_uObjectSize";
openfl_display_Shader.uTextureSize = "openfl_uTextureSize";
openfl_events_Event.ACTIVATE = "activate";
openfl_events_Event.ADDED = "added";
openfl_events_Event.ADDED_TO_STAGE = "addedToStage";
openfl_events_Event.CHANGE = "change";
openfl_events_Event.COMPLETE = "complete";
openfl_events_Event.DEACTIVATE = "deactivate";
openfl_events_Event.ENTER_FRAME = "enterFrame";
openfl_events_Event.MOUSE_LEAVE = "mouseLeave";
openfl_events_Event.OPEN = "open";
openfl_events_Event.REMOVED = "removed";
openfl_events_Event.REMOVED_FROM_STAGE = "removedFromStage";
openfl_events_Event.RENDER = "render";
openfl_events_Event.RESIZE = "resize";
openfl_events_TextEvent.TEXT_INPUT = "textInput";
openfl_events_FocusEvent.FOCUS_IN = "focusIn";
openfl_events_FocusEvent.FOCUS_OUT = "focusOut";
openfl_events_FullScreenEvent.FULL_SCREEN = "fullScreen";
openfl_events_GameInputEvent.DEVICE_ADDED = "deviceAdded";
openfl_events_GameInputEvent.DEVICE_REMOVED = "deviceRemoved";
openfl_events_HTTPStatusEvent.HTTP_STATUS = "httpStatus";
openfl_events_IOErrorEvent.IO_ERROR = "ioError";
openfl_events_KeyboardEvent.KEY_DOWN = "keyDown";
openfl_events_KeyboardEvent.KEY_UP = "keyUp";
openfl_events_MouseEvent.CLICK = "click";
openfl_events_MouseEvent.DOUBLE_CLICK = "doubleClick";
openfl_events_MouseEvent.MIDDLE_CLICK = "middleClick";
openfl_events_MouseEvent.MIDDLE_MOUSE_DOWN = "middleMouseDown";
openfl_events_MouseEvent.MIDDLE_MOUSE_UP = "middleMouseUp";
openfl_events_MouseEvent.MOUSE_DOWN = "mouseDown";
openfl_events_MouseEvent.MOUSE_MOVE = "mouseMove";
openfl_events_MouseEvent.MOUSE_OUT = "mouseOut";
openfl_events_MouseEvent.MOUSE_OVER = "mouseOver";
openfl_events_MouseEvent.MOUSE_UP = "mouseUp";
openfl_events_MouseEvent.MOUSE_WHEEL = "mouseWheel";
openfl_events_MouseEvent.RIGHT_CLICK = "rightClick";
openfl_events_MouseEvent.RIGHT_MOUSE_DOWN = "rightMouseDown";
openfl_events_MouseEvent.RIGHT_MOUSE_UP = "rightMouseUp";
openfl_events_ProgressEvent.PROGRESS = "progress";
openfl_events_SecurityErrorEvent.SECURITY_ERROR = "securityError";
openfl_media_Sound.__registeredSounds = new haxe_ds_StringMap();
openfl_ui_GameInput.numDevices = 0;
openfl_ui_GameInput.__devices = new haxe_ds_ObjectMap();
openfl_ui_GameInput.__instances = [];
ru_octasoft_oem_designer_SizeUtil.UNIT = 100.;
ru_octasoft_oem_designer_events_ClearEvent.NAME = "CLEAR_ALL";
ru_octasoft_oem_designer_events_DeleteEvent.NAME = "DELETE_FIGURE";
ru_octasoft_oem_designer_events_FigureAddedEvent.NAME = "FIGURE_ADDED";
ru_octasoft_oem_designer_events_FigureDeletedEvent.NAME = "FIGURE_DELETED";
ru_octasoft_oem_designer_events_FigureDraggedEvent.NAME = "FIGURE_DRAGGED";
ru_octasoft_oem_designer_events_FigureMovedEvent.NAME = "FIGURE_MOVED";
ru_octasoft_oem_designer_events_FigureRotatedEvent.NAME = "FIGURE_ROTATED";
ru_octasoft_oem_designer_events_FigureSelectedEvent.NAME = "FIGURE_SELECTED";
ru_octasoft_oem_designer_events_RedoEvent.NAME = "REDO";
ru_octasoft_oem_designer_events_UndoEvent.NAME = "UNDO";
ru_octasoft_oem_designer_events_ZoomEvent.ZOOM_IN = "ZOOM_IN";
ru_octasoft_oem_designer_events_ZoomEvent.ZOOM_OUT = "ZOOM_OUT";
ru_octasoft_oem_designer_figures_Figure.seq = 0;
ApplicationMain.main();
})(typeof console != "undefined" ? console : {log:function(){}}, typeof window != "undefined" ? window : exports, typeof window != "undefined" ? window : typeof global != "undefined" ? global : typeof self != "undefined" ? self : this);
