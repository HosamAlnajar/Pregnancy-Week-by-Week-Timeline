let timelinesData = {};

$.fn.loadTimeline = function () {

	const timeLinePosts = Post_Timeline.TimelinePosts;

	let data = [];

	let timeLineItem = {text: "",image:"", link:"", week: ""};

	let weekNumber = 0;

	Object.keys(timeLinePosts).forEach(function(key,index) {

		if ( key.includes('link') ){
			timeLineItem.link = timeLinePosts[key] ? timeLinePosts[key] : '#';
		}
		if ( key.includes('image') ){
			timeLineItem.image = timeLinePosts[key];
		}
		if ( key.includes('text') ){
			weekNumber++;
			timeLineItem.text = timeLinePosts[key];
			timeLineItem.week = weekNumber;
			data.push(timeLineItem);
			timeLineItem = {text: "", image:"", link:"", week: ""};
		}
	});

	let orderedData = data;

	const id = $( this ).attr( "id" );

	timelinesData[ id ] = orderedData;

	$( this ).attr( "class", "timeline-box" );

	$( this ).append(
		'<div class="line-timeline"></div><ul class="father-box" style="transform: translateX(0px);"></ul>'
	);

	// Add incoming array data to the timeline
	for ( let item of orderedData ) {

		const { image, week, text, link } = item;

		if ( text ) {
			$( this ).find( ".father-box" ).append( `
       <li class="data-box"> 
          <div class="data" style="background-image:url('${image}'); "> 
          <a href="${link}" class="timeline-post-link">${text}</a>  
          </div>
          <div class="vertical-line"></div>
          <div class="ball"></div>
          <p class="find-week">${week}</p>
        </li>
               

      ` );
		}
	}

	// Handle events
	$( this ).turnoffEvents();

	dragEvents( orderedData, this, $ );

	/** @description Start Drag events */
	function dragEvents( orderedData, context, $ ) {
		// Total width of scroll box
		let scrollContentWidth = 0;
		orderedData.forEach( ( _, i ) => {
			const width = context.find( ".father-box li" ).get( i ).scrollWidth;
			scrollContentWidth += width;
		} );

		// handlers of events
		/**@type {boolean} */
		let canDrag;

		/**@type {number} */
		let lastX = 0;

		context.on( {
			mousemove: e => {
				if ( canDrag ) {
					// get actual width of the box if it's resized and decrement in the max drag width
					const canMove =
						scrollContentWidth - Math.floor( $( context ).width() * 0.40 );
					const maxDragWidth = canMove >= 0 ? canMove : 0;

					console.log(maxDragWidth);
					const style = $( context )
						.find( ".father-box" )
						.attr( "style" );
					const findTransform = style.indexOf( "translate" );
					const translate = style.substring( findTransform );
					const actualPositionX = Number( translate.replace( /\D/g, "" ) );

					if ( !lastX ) {
						lastX = e.pageX;
					}

					const movedValue = Math.abs( lastX ) - e.pageX;
					let finalPositionX = Math.abs( actualPositionX + movedValue );
					lastX = e.pageX;

					if ( actualPositionX + movedValue <= 0 ) {
						finalPositionX = 0;
					}
					if ( actualPositionX + movedValue >= maxDragWidth ) {
						finalPositionX = maxDragWidth;
					}

					context
						.find( ".father-box" )
						.attr( "style", `transform: translate(-${finalPositionX}px)` );
				}
			},
			mousedown: e => {
				lastX = e.pageX;
				canDrag = true;
				$( ".timeline-box" ).attr( "style", "cursor: grabbing" );
			},
			mouseup: _ => {
				canDrag = false;
				$( ".timeline-box" ).attr( "style", "cursor: grab" );
			},
			mouseleave: _ => {
				canDrag = false;
			}
		} );
	}

};

/**@description Turnoff all events of the timeline */
$.fn.turnoffEvents = function () {
	$( this ).off();
};

/**@description Destroys the timeline */
$.fn.destroyTimeine = function () {
	$( this ).empty();
	const id = $( this ).attr( "id" );
	delete timelinesData[ id ];
};

/**@description Go to a passed week */
$.fn.goToweek = function ( week ) {
	const widthBox = $( this ).width();
	const timelineId = $( this ).attr( "id" );
	const timelineData = timelinesData[ timelineId ];
	let fullScrollWidth = 0;
	let widthUntilweek = 0;

	if ( timelineData ) {
		timelineData.forEach( ( _, i ) => {
			const width = $( this )
				.find( ".father-box li" )
				.get( i ).scrollWidth;
			fullScrollWidth += width;
		} );

		// If can scroll
		if ( fullScrollWidth > widthBox ) {
			let maxScroll = Math.floor( fullScrollWidth - widthBox );

			$( this )
				.find( "li" )
				.find( ".find-week" )
				.each( i => {
					const { innerText, scrollWidth } = $( this )
						.find( "li" )
						.find( ".find-week" )
						.get( i );
					if ( innerText !== week ) {
						widthUntilweek += scrollWidth + 18;
						maxScroll += 54;
					} else {
						if ( widthUntilweek > maxScroll ) {
							widthUntilweek = maxScroll;
						}
						$( this )
							.find( ".father-box" )
							.attr(
								"style",
								`transition: transform 0.5s;transform: translate(-${widthUntilweek}px)`
							);
						return;
					}
				} );
		}
	}
};
