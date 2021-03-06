﻿$('.form_datetime').datetimepicker({
		    weekStart: 1,
		    todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 1,
		    showMeridian: 1,
            startDate: '-1y'
		});
		$('.form_date').datetimepicker({
            initialDate: new Date(),
		    weekStart: 1,
		    todayBtn:  1,
			autoclose: true,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0,


		});
        $('.to_date').datetimepicker({
            initialDate: new Date(),
            weekStart: 1,
            todayBtn:  1,
            autoclose: true,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,


        });
		$('.form_time').datetimepicker({
		    weekStart: 1,
		    todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 1,
			minView: 0,
			maxView: 1,
			forceParse: 0
		});
		$(function () {
            $('#datetimepicker1').datetimepicker();
        });
		 $(function() {
			    // Bootstrap DateTimePicker v3
			    $('#datetimepicker4').datetimepicker({
			      pickTime: false
			    });
			    // Bootstrap DateTimePicker v4
			    $('#datetimepicker3').datetimepicker({
			      format: 'DD/MM/YYYY'
			    });
			  });
