{literal}
    <script type="text/javascript">
        $(function () {
            var $start = $('#sDateT1');

            $start.datepicker({
                maxDate: '+ 1 month',
                onSelect: function (selectedDate) {
					return SubmitData();
                }
            });
        });
        function CreateAllReport() {
            sConfirm = "Создать всё?";

            href_post = confirm(sConfirm);
            if (href_post) {
                document.getElementById('Report').value = '0';
                document.getElementById('SendReport').value = '0';
                document.getElementById('ChildrenId').value = '0';
                document.getElementById('SendAllReport').value = '0';
                document.getElementById('CreateAllReport').value = '1';
                document.getElementById('report_form').submit();
                document.getElementById('CreateAllReport').value = '0';
            }
            return href_post;
        }


        function SubmitData() {
            //document.getElementById('Report').value = '0';
            //document.getElementById('SendReport').value = '0';
            //document.getElementById('ChildrenId').value = '0'
            document.getElementById('report_form').submit();
            return false;
        }

    </script>
<style type="text/css">
	.yellowbk{
		background-color:yellow;
	}
	.title {
        margin: 20px 0px;
        font-size: 23px;
        font-weight: bold;
    }

    .top {
        border-top: 1px solid #E3E3E3;
        border-bottom: 1px solid #E3E3E3;
        background-color: #F5F5F5;
        text-align: center;
        padding: 15px 52px;
    }

    .top table {
        margin: 0px;
        padding: 0px;
        border: none;
        border-collapse: collapse;
    }

    .top td {
        border: none;
        padding: 0px;
        margin: 0px;
    }

    span.input_element {
        white-space: nowrap;
        padding: 0px 35px 0px 0px;
        line-height: 32px;
        float: left;
    }

    .mainTable {
        overflow: auto;
        position: relative;
    }
    .mainTable table {
        position: relative;
        overflow: auto;
        }
	.ritz .waffle a {
		color: inherit;
	}

	.ritz .waffle .s26 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 9pt;
		vertical-align: bottom;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s32 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s22 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s49 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 8pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s5 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s56 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 8pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s60 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s33 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #b6d7a8;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s54 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: right;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s68 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: center;
		text-decoration: underline;
		-webkit-text-decoration-skip: none;
		text-decoration-skip-ink: none;
		color: #1155cc;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s11 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s42 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		font-style: italic;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s2 {
		text-overflow: ellipsis;
		overflow: hidden;
		vertical-align: top;
		display: inline-block;
		height: fit-content;
		border-radius: 8px;
	}

	.ritz .waffle .s4 {
		border-bottom: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s7 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		border-left: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s37 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s63 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #93c47d;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s59 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #b6d7a8;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 8pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s76 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #d9ead3;
		text-align: center;
		text-decoration: underline;
		-webkit-text-decoration-skip: none;
		text-decoration-skip-ink: none;
		color: #1155cc;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s16 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s19 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s20 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ea9999;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s23 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s25 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		font-style: italic;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s66 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: center;
		text-decoration: underline;
		-webkit-text-decoration-skip: none;
		text-decoration-skip-ink: none;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s72 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #d9ead3;
		text-align: center;
		color: #000000;
		font-family: 'Arial';
		font-size: 12pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s51 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 8pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s35 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffff00;
		text-align: center;
		font-style: italic;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s50 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 8pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s73 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #d9ead3;
		text-align: center;
		text-decoration: underline;
		-webkit-text-decoration-skip: none;
		text-decoration-skip-ink: none;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s77 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #d9ead3;
		text-align: center;
		font-style: italic;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s8 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s57 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffff00;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 8pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s62 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #93c47d;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s78 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #d9ead3;
		text-align: center;
		text-decoration: underline;
		-webkit-text-decoration-skip: none;
		text-decoration-skip-ink: none;
		color: #1155cc;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s75 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #d9ead3;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s40 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s24 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s3 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s69 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: center;
		font-style: italic;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s53 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
	}

	.ritz .waffle .s12 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s74 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #d9ead3;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s27 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		border-left: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s52 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 12pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s28 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #d9d9d9;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s55 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 8pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s17 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: center;
		font-style: italic;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s45 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: center;
		font-style: italic;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s46 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #b6d7a8;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s41 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s38 {
		border-bottom: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s71 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s15 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s61 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s43 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s64 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #93c47d;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s70 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: center;
		text-decoration: underline;
		-webkit-text-decoration-skip: none;
		text-decoration-skip-ink: none;
		color: #1155cc;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s31 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ff0000;
		text-align: center;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s36 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: center;
		font-style: italic;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: top;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s48 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: right;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s58 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #efefef;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 8pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s65 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: center;
		color: #000000;
		font-family: 'Arial';
		font-size: 12pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s47 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s79 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #d9ead3;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s44 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s21 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 8pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s0 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		border-left: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s14 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		text-decoration: underline;
		-webkit-text-decoration-skip: none;
		text-decoration-skip-ink: none;
		color: #1155cc;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s13 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s34 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Arial';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s67 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s9 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		border-left: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		font-weight: bold;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s29 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s39 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		text-decoration: underline;
		-webkit-text-decoration-skip: none;
		text-decoration-skip-ink: none;
		color: #1155cc;
		font-family: 'docs-Tahoma',Arial;
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s30 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 9pt;
		vertical-align: bottom;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s1 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #ffffff;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s10 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #cccccc;
		text-align: center;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space: normal;
		overflow: hidden;
		word-wrap: break-word;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s6 {
		border-bottom: 1px SOLID #ffffff;
		border-right: 1px SOLID #ffffff;
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'docs-Inconsolata',Arial;
		font-size: 11pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}

	.ritz .waffle .s18 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		background-color: #fff2cc;
		text-align: left;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: bottom;
		white-space: nowrap;
		direction: ltr;
		padding: 2px 3px 2px 3px;
	}
</style>
{/literal}

<div class="title">
<span style="float: right; font-size: 12px; margin-top: 5px;" class="no_print">
      <a class="header__user-item header__user-item_img" href="javascript:window.print()"><img src="images/print.gif" title="Печать отчёта"></a>
      <a class="header__user-item header__user-item_img header__user-item--settings" href="edit_report.php?report=420" title="Настройки"></a>
</span>
    Отчёт педагогов
</div>
<div class="top input_element">
    <table style="margin: 0px auto;">
        <tr>
            <td>&nbsp;&nbsp;
                <input type="submit" value="Обновить" class="no_print btn btn-default btn-sm"
                       onclick="return SubmitData();"/>
			&nbsp;&nbsp;
            </td>
			<td>
				<input type="file" id="upload1" name="Foto1" class="no_print btn btn-default btn-sm" title="Добавить фото 1"/>
			</td>
			<td>
				<input type="file" id="upload2" name="Foto2" class="no_print btn btn-default btn-sm" title="Добавить фото 2"/>
			</td>
            <td>
				&nbsp;&nbsp;
				<input type="submit" value="Загрузить" class="no_print btn btn-default btn-sm" onclick="return SubmitData();"/>
            </td>
        </tr>
    </table>
</div>
		<div class="ritz  mainTable"
		     dir="ltr">
			<table class="waffle mainTable"
			       cellspacing="0"
			       cellpadding="0">
					<tr style="height:0px;">
						<th />
						<th style="width:22px;" class="column-headers-background"/>
						<th style="width:230px;" class="column-headers-background"/>
						<th style="width:65px;" class="column-headers-background"/>
						<th style="width:96px;" class="column-headers-background"/>
						<th style="width:36px;" class="column-headers-background"/>
						<th style="width:44px;" class="column-headers-background"/>
						<th style="width:139px;" class="column-headers-background"/>
						<th style="width:63px;" class="column-headers-background"/>
						<th style="width:55px;" class="column-headers-background"/>
						<th  style="width:45px;" class="column-headers-background"/>
						<th  style="width:68px;" class="column-headers-background"/>
						<th  style="width:70px;" class="column-headers-background"/>
						<th  style="width:59px;" class="column-headers-background"/>
						<th  style="width:67px;" class="column-headers-background"/>
						<th  style="width:56px;" class="column-headers-background"/>
						<th  style="width:56px;" class="column-headers-background"/>
						<th  style="width:42px;" class="column-headers-background"/>
						<th  style="width:46px;" class="column-headers-background"/>
						<th  style="width:108px;" class="column-headers-background"/>
						<th  style="width:83px;" class="column-headers-background"/>
						<th  style="width:72px;" class="column-headers-background"/>
						<th  style="width:45px;" class="column-headers-background"/>
						<th style="width:45px;" class="column-headers-background"/>
						<th  style="width:62px;" class="column-headers-background"/>
						<th  style="width:76px;" class="column-headers-background"/>
						<th style="width:91px;" class="column-headers-background"/>
					</tr>
			<tbody>
					<tr style="height: 28px">
						<th id="685479336R0"
						    style="height: 28px;"
						    class="row-headers-background">
						</th>
						<td class="s0"
						    dir="ltr"
						    colspan="2">Название группы</td>
						<td class="s1"
						    dir="ltr"
						    colspan="2" id="sGroupname" >
                <select name="iGroupId" onchange="return  SubmitData();">
                    {foreach from=$vGroups item=data name="rows"}
                        {if $data.GroupId == $iGroupId}
                            <option selected value="{$data.GroupId}">{$data.GroupName}</option>
                        {else}
                            <option value="{$data.GroupId}">{$data.GroupName}</option>
                        {/if}
                    {/foreach}
                </select>
						</td>
						<td class="s0"
						    dir="ltr"
						    colspan="2">Кабинет</td>
						<td class="s1"
						    dir="ltr"
						    colspan="2">{$sCabinetH1}</td>
						<td class="s0"
						    dir="ltr"
						    colspan="2">Формат план</td>
						<td class="s3"
						    dir="ltr">{$sFormatPlanL1}</td>
						<td class="s0"
						    dir="ltr"
						    colspan="2">Формат факт</td>
						<td class="s1"
						    dir="ltr">
							{if $bIsAdmin}
								<select name="sFormatFactO1" onchange="return  SubmitData();">
									{$vForms = ['очно', 'онлайн']}
									{foreach $vForms as $vForm}
										{if $vForm == $sFormatPlanL1}
											<option selected value="{$vForm}">{$vForm}</option>
										{else}
											<option value="{$vForm}">{$vForm}</option>
										{/if}
									{/foreach}
								</select>
							{else}
								<input type=hidden name="sFormatFactO1" id="sFormatFactO1" value="{$sFormatPlanL1}"/>{$sFormatPlanL1}
							{/if}
						</td>
						<td class="s0"
						    dir="ltr"
						    colspan="4">Информация по занятию</td>
						<td class="s4"
						    colspan="2">{$sDateTimeT1}
						<!--<input type="text" name="sDateT1" id="sDateT1" value="{$sDateT1}" size="10" class="datepicker form-control form-control-160"/>-->
						</td>
						<td class="s4"
						    colspan="2">{$sWeekDayV1}</td>
						<td class="s1"
						    colspan="2">{$sLessonTimeX1}</td>
						<td class="s5"
						    dir="ltr"/>
						<td class="s6"
						    dir="ltr"/>
						<td class="s6"
						    dir="ltr"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R1"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s7"
						    dir="ltr"
						    colspan="2">Преподаватель 1</td>
						<td class="s8"
						    dir="ltr"
						    colspan="3">{$sTeacherFioFactD2}</td>
						<td class="s9"
						    dir="ltr"
						    rowspan="2">Место</td>
						<td class="s3"
						    dir="ltr"
						    colspan="2"
						    rowspan="2">{$sDepartmentNameH2}</td>
						<td class="s0"
						    dir="ltr"
						    colspan="2">Адрес</td>
						<td class="s3"
						    dir="ltr"
						    colspan="4">{$sDepartmentAddressL2}</td>
						<td class="s9"
						    dir="ltr"
						    colspan="2">Неделя/Урок</td>
						<td class="s10"
						    dir="ltr">{$sWeekLessonR2}</td>
						<td class="s10"
						    dir="ltr">{$sWeekLessonR2}</td>
						<td class="s0"
						    dir="ltr">Учебный год</td>
						<td class="s1">{$sAcademicYearU2}</td>
						<td class="s9"
						    dir="ltr"
						    colspan="2">Возраст</td>
						<td class="s9"
						    dir="ltr"
						    colspan="2">{$sProgramAgeX2}</td>
						<td class="s6"/>
						<td class="s11"/>
						<td class="s6"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R2"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s7"
						    dir="ltr"
						    colspan="2">Преподаватель 2</td>
						<td class="s3"
						    dir="ltr"
						    colspan="3">
							{if $bIsAdmin}
								<select name="iTeacherId2">
									{foreach from=$vTeachers item=data name="rows"}
										<option value="{$data.TeacherId}">{$data.TeacherName}</option>
									{/foreach}
								</select>
							{/if}
						</td>
						<td class="s7"
						    dir="ltr"
						    colspan="2">ДЗ</td>
						<td class="s12"
						    colspan="4">Код задания {$sProgramForYearL3}</td>
						<td class="s13"
						    dir="ltr"
						    colspan="4">{$sSubsectionP3}</td>
						<td class="s13"
						    dir="ltr"
						    colspan="5">{$sJobNameT3}</td>
						<td class="s14"
						    dir="ltr">
							{if $sPrintOutsPdf != ""}
							<a target="_blank" href="{$sPrintOutsPdf}">ссылка</a>
							{/if}
						</td>
						<td class="s15"/>
						<td class="s16"/>
						<td class="s16"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R3"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s9"
						    dir="ltr"
						    colspan="2"
						    rowspan="5">Комментарий на следующее занятие</td>
						<td class="s17"
						    dir="ltr"
						    colspan="3"
						    rowspan="5"/>
						<td class="s9"
						    dir="ltr"
						    colspan="3">Комментарий по уровню темы</td>
						<td class="s18"
						    dir="ltr"
						    colspan="4"/>
						<td class="s19"
						    dir="ltr"
						    colspan="4"/>
						<td class="s19"
						    dir="ltr"
						    colspan="4"/>
						<td class="s19"
						    dir="ltr"
						    colspan="4"/>
						<td class="s15"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R4"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s9"
						    dir="ltr"
						    colspan="3">Комментарий по уровню задачи</td>
						<td class="s19"
						    dir="ltr"
						    colspan="3"/>
						<td class="s13{if $sM5=='нужно'} yellowbk{/if}"
						    dir="ltr">{$sM5}</td>
						<td class="s19"
						    dir="ltr"
						    colspan="3"/>
						<td class="s13{if $sQ5=='нужно'} yellowbk{/if}"
						    dir="ltr">{$sQ5}</td>
						<td class="s19"
						    dir="ltr"
						    colspan="3"/>
						<td class="s13{if $sU5=='нужно'} yellowbk{/if}"
						    dir="ltr">{$sU5}</td>
						<td class="s19"
						    dir="ltr"
						    colspan="3"/>
						<td class="s13{if $sY5=='нужно'} yellowbk{/if}"
						    dir="ltr">{$sY5}</td>
						<td class="s21"
						    dir="ltr"/>
						<td class="s11"/>
						<td class="s11"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R5"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s0"
						    dir="ltr"
						    colspan="3">Раздел</td>
						<td class="s12"
						    colspan="4">{$sSectionJ6}</td>
						<td class="s12"
						    colspan="4">{$sSectionN6}</td>
						<td class="s12"
						    colspan="4">{$sSectionR6}</td>
						<td class="s12"
						    colspan="4">{$sSectionV6}</td>
						<td class="s22"/>
						<td class="s11"/>
						<td class="s11"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R6"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s0"
						    dir="ltr"
						    colspan="3">Подраздел</td>
						<td class="s12"
						    colspan="4">{$sSubsectionJ7}</td>
						<td class="s12"
						    colspan="4">{$sSubsectionN7}</td>
						<td class="s12"
						    colspan="4">{$sSubsectionR7}</td>
						<td class="s12"
						    colspan="4">{$sSubsectionV7}</td>
						<td class="s22"/>
						<td class="s11"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R7"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s0"
						    dir="ltr"
						    colspan="3">Тема</td>
						<td class="s12"
						    colspan="4">{$sTopicJ8}</td>
						<td class="s12"
						    colspan="4">{$sTopicN8}</td>
						<td class="s12"
						    colspan="4">{$sTopicR8}</td>
						<td class="s12"
						    colspan="4">{$sTopicV8}</td>
						<td class="s23"/>
						<td class="s24"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
					</tr>
					<tr style="height: 49px">
						<th id="685479336R8"
						    style="height: 49px;"
						    class="row-headers-background">
						</th>
						<td class="s0"
						    dir="ltr"
						    colspan="2"
						    rowspan="3">Список учеников</td>
						<td class="s0"
						    dir="ltr"
						    rowspan="3">Группа</td>
						<td class="s9"
						    dir="ltr"
						    rowspan="3">Откуда/Куда</td>
						<td class="s9"
						    dir="ltr"
						    rowspan="3">∑ ☆</td>
						<td class="s3"
						    dir="ltr"
						    rowspan="3">+☆</td>
						<td class="s9"
						    dir="ltr"
						    rowspan="3">Пробное / Отработка</td>
						<td class="s3"
						    dir="ltr"
						    rowspan="2">Псщ</td>
						<td class="s25"
						    dir="ltr"
						    colspan="3"
						    rowspan="2">{$sJobNameJ9}</td>
						<td class="s25"
						    dir="ltr"
						    rowspan="2">{$sJobCodeM9}</td>
						<td class="s25"
						    dir="ltr"
						    colspan="3"
						    rowspan="2">{$sJobNameN9}</td>
						<td class="s25"
						    dir="ltr"
						    rowspan="2">{$sJobCodeQ9}</td>
						<td class="s25"
						    dir="ltr"
						    colspan="3"
						    rowspan="2">{$sJobNameR9}</td>
						<td class="s25"
						    dir="ltr"
						    rowspan="2">{$sJobCodeU9}</td>
						<td class="s25"
						    dir="ltr"
						    colspan="3"
						    rowspan="2">{$sJobNameV9}</td>
						<td class="s25"
						    dir="ltr"
						    rowspan="2">{$sJobCodeY9}</td>
						<td class="s25"
						    dir="ltr"
						    rowspan="3">Комментарий</td>
						<td class="s25"
						    dir="ltr"
						    rowspan="3">Заказ подарков (доставка)</td>
						<td class="s11"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R9"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s11"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R10"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s0"
						    dir="ltr">ID</td>
						<td class="s12"
						    dir="ltr"
						    colspan="4">Код задания {$sJobCodeJ11}</td>
						<td class="s12"
						    dir="ltr"
						    colspan="4">Код задания {$sJobCodeN11}</td>
						<td class="s12"
						    dir="ltr"
						    colspan="4">Код задания {$sJobCodeR11}</td>
						<td class="s12"
						    dir="ltr"
						    colspan="4">Код задания {$sJobCodeV11}</td>
						<td class="s26"/>
					</tr>
				{foreach from=$lines item=data name="rows"}
					<tr style="height: 20px">
						<th id="685479336R11"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s27"
						    dir="ltr">{$data.iPos}<input name="iB{$data.iPos}" type="hidden" value=""/></td>
						<td class="s28"
						    dir="ltr">{$data.ChildrenFIO}</td>
						<td class="s12"
						    dir="ltr">{$data.ClassGroup}</td>
						<td class="s12">{$data.PickGivesName}</td>
						<td class="s12" dir="ltr">{$data.Stars}</td>
						<td class="s12" dir="ltr">
							<select name="iG{$data.iPos}">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s29">
							<select name="iH{$data.iPos}">
								{if $data.Trial=='Пробное' || $data.Trial=='пробное'}
									<option value=""></option>
									<option value="Пробное" selected style="background-color:green;">Пробное</option>
								{else}
									<option value="" selected></option>
									<option value="Пробное" style="background-color:green;">Пробное</option>
								{/if}
							</select>
							/{$data.WorkingOff}
						</td>
						<td class="s12" dir="ltr">
							<select name="iI{$data.iPos}">
								<option value="н" selected>н</option>
								<option value=""></option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select name="iJ{$data.iPos}">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select name="iL{$data.iPos}">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select name="iN{$data.iPos}">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select name="iP{$data.iPos}">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select name="iR{$data.iPos}">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select name="iT{$data.iPos}">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select name="iV{$data.iPos}">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select name="iX{$data.iPos}">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s30">{$data.WorkingOffReport}</td>
						<td class="s29"
						    dir="ltr">
							{if $bIsAdmin}
								<select name="iZ{$data.iPos}">
									{foreach from=$vGifts item=data name="rows"}
										<option value="{$data.GiftId}">{$data.GiftName}</option>
									{/foreach}
								</select>
							{/if}
						</td>
						<td class="s26"/>
					</tr>
				{/foreach}
					<tr style="height: 53px">
						<th id="685479336R27"
						    style="height: 53px;"
						    class="row-headers-background">
						</th>
						<td class="s11"/>
						<td class="s32"/>
						<td class="s33"
						    dir="ltr"
						    colspan="2"
						    rowspan="2">Оплата</td>
						<td class="s1"
						    dir="ltr"
						    colspan="2"
						    rowspan="2"/>
						<td class="s34"
						    dir="ltr"
						    colspan="2"
						    rowspan="2">Чему учились?</td>
						<td class="s17"
						    dir="ltr"
						    colspan="4"
						    rowspan="2">
								<select name="iJ28">
									{foreach from=$vWhatDidLearnJ28 item=data name="rows"}
										<option value="{$data.WhatDidLearnId}">{$data.WhatDidLearnName}</option>
									{/foreach}
								</select></td>
						<td class="s35"
						    dir="ltr"
						    colspan="4"
						    rowspan="2">								<select name="iJ28">
									{foreach from=$vWhatDidLearnN28 item=data name="rows"}
										<option value="{$data.WhatDidLearnId}">{$data.WhatDidLearnName}</option>
									{/foreach}
								</select></td>
						<td class="s17"
						    dir="ltr"
						    colspan="4"
						    rowspan="2">								<select name="iJ28">
									{foreach from=$vWhatDidLearnR28 item=data name="rows"}
										<option value="{$data.WhatDidLearnId}">{$data.WhatDidLearnName}</option>
									{/foreach}
								</select></td>
						<td class="s17"
						    dir="ltr"
						    colspan="4"
						    rowspan="2">								<select name="iJ28">
									{foreach from=$vWhatDidLearnV28 item=data name="rows"}
										<option value="{$data.WhatDidLearnId}">{$data.WhatDidLearnName}</option>
									{/foreach}
								</select></td>
						<td class="s36"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
					</tr>
					<tr style="height: 2px">
						<th id="685479336R28"
						    style="height: 2px;"
						    class="row-headers-background">
						</th>
						<td class="s11"/>
						<td class="s32"/>
						<td class="s36"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R29"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s37"
						    dir="ltr"
						    colspan="2"/>
						<td class="s38"/>
						<td class="s11"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s39"
						    dir="ltr"/>
						<td class="s11"/>
						<td class="s11"/>
					</tr>
					<tr style="height: 40px">
						<th id="685479336R30"
						    style="height: 40px;"
						    class="row-headers-background">
						</th>
						<td class="s11"/>
						<td class="s40"/>
						<td class="s40"/>
						<td class="s41"/>
						<td class="s0"
						    dir="ltr"
						    colspan="4">Замечания по заданиям</td>
						<td class="s42"
						    dir="ltr"
						    colspan="4"/>
						<td class="s42"
						    dir="ltr"
						    colspan="4"/>
						<td class="s42"
						    dir="ltr"
						    colspan="4"/>
						<td class="s42"
						    dir="ltr"
						    colspan="4"/>
						<td class="s40"/>
						<td class="s11"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
					</tr>
					<tr style="height: 18px">
						<th id="685479336R31"
						    style="height: 18px;"
						    class="row-headers-background">
						</th>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s43"/>
						<td class="s43"/>
						<td class="s24"/>
						<td class="s44"
						    dir="ltr"/>
						<td class="s44"
						    dir="ltr"/>
						<td class="s24"/>
						<td class="s45"
						    dir="ltr"/>
						<td class="s45"
						    dir="ltr"/>
						<td class="s24"/>
						<td class="s24"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s11"/>
						<td class="s40"/>
						<td class="s11"
						    dir="ltr"/>
						<td class="s11"
						    dir="ltr"/>
					</tr>
					<tr style="height: 58px">
						<th id="685479336R32"
						    style="height: 58px;"
						    class="row-headers-background">
							<div class="row-header-wrapper"
							     style="line-height: 58px"></div>
						</th>
						<td class="s11"/>
						<td class="s32"/>
						<td class="s9"
						    dir="ltr"
						    colspan="6">Комментарий с предыдущего занятия.</td>
						<td class="s46"
						    dir="ltr"
						    colspan="4">{$sPreviosCommentJ33}</td>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s24"/>
							<td class="s40"/>
							<td class="s11"/>
							<td class="s11"/>
						</tr>
						<tr style="height: 39px">
							<th id="685479336R33"
							    style="height: 39px;"
							    class="row-headers-background">
								<div class="row-header-wrapper"
								     style="line-height: 39px"></div>
							</th>
							<td class="s11"/>
							<td class="s41"/>
							<td class="s9"
							    dir="ltr"
							    colspan="6">Комментарий по теме</td>
							<td class="s3"
							    colspan="4">{$sThemeJ34}</td>
							<td class="s46"
							    dir="ltr"
							    colspan="4">{$sThemeN34}</td>
								<td class="s3"
								    colspan="4">{$sThemeR34}</td>
								<td class="s3"
								    colspan="4">{$sThemeV34}</td>
								<td class="s11"/>
								<td class="s11"
								    dir="ltr"/>
								<td class="s11"
								    dir="ltr"/>
							</tr>
							<tr style="height: 51px">
								<th id="685479336R34"
								    style="height: 51px;"
								    class="row-headers-background">
									<div class="row-header-wrapper"
									     style="line-height: 51px"></div>
								</th>
								<td class="s11"/>
								<td class="s41"/>
								<td class="s9"
								    dir="ltr"
								    colspan="6">Комментарий по заданию</td>
								<td class="s3"
								    colspan="4">{$sTaskJ35}</td>
								<td class="s3"
								    colspan="4">{$sTaskN35}</td>
								<td class="s3"
								    colspan="4">{$sTaskR35}</td>
								<td class="s46"
								    dir="ltr"
								    colspan="4">{$sTaskV35}</td>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
								</tr>
								<tr style="height: 20px">
									<th id="685479336R35"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s47"
									    dir="ltr"/>
									<td class="s47"
									    dir="ltr"/>
								</tr>
								<tr style="height: 20px">
									<th id="685479336R36"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s47"/>
								</tr>
								<tr style="height: 20px">
									<th id="685479336R37"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s11"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s50"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"
									    colspan="3"/>
									<td class="s50"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"
									    colspan="3"/>
									<td class="s51"
									    dir="ltr"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
								</tr>
								<tr style="height: 20px">
									<th id="685479336R38"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s11"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s48"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s50"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s50"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s50"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s49"
									    dir="ltr"/>
									<td class="s51"
									    dir="ltr"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
								</tr>
								<tr style="height: 20px">
									<th id="685479336R40"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s11"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s5"
									    dir="ltr"/>
									<td class="s11"/>
									<td class="s11"/>
								</tr>
								<tr style="height: 20px">
									<th id="685479336R41"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s11"/>
									<td class="s40"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s52"
									    dir="ltr"
									    colspan="6">Рекомендации</td>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s5"
									    dir="ltr"/>
									<td class="s11"/>
									<td class="s11"/>
								</tr>

				{foreach from=$lines item=data name="rows"}
							<tr style="height: 20px">
									<th id="685479336R43"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s53"/>
									<td class="s54">{$data.iPos}</td>
									<td class="s29"
									    colspan="6">{$data.ChildrenFIO}</td>
									<td class="s55"
									    dir="ltr"
									    colspan="4">
										<select name="iJ44_{$data.iPos}">
											{foreach from=$vRecomendationJ44 item=data name="rows"}
												{if $data.RecomendationLink == ""}
													<option value="{$data.RecomendationId}">{$data.RecomendationText}</option>
												{else}
													<option value="{$data.RecomendationId}"><a target="_blank" href="{$data.RecomendationLink}">{$data.RecomendationText}</a></option>
												{/if}
											{/foreach}
										</select>
									</td>
									<td class="s55"
									    dir="ltr"
									    colspan="4">
										<select name="iN44_{$data.iPos}">
											{foreach from=$vRecomendationN44 item=data name="rows"}
												{if $data.RecomendationLink == ""}
													<option value="{$data.RecomendationId}">{$data.RecomendationText}</option>
												{else}
													<option value="{$data.RecomendationId}"><a target="_blank" href="{$data.RecomendationLink}">{$data.RecomendationText}</a></option>
												{/if}
											{/foreach}
										</select>
									</td>
									<td class="s55"
									    dir="ltr"
									    colspan="4">
										<select name="iR44_{$data.iPos}">
											{foreach from=$vRecomendationR44 item=data name="rows"}
												{if $data.RecomendationLink == ""}
													<option value="{$data.RecomendationId}">{$data.RecomendationText}</option>
												{else}
													<option value="{$data.RecomendationId}"><a target="_blank" href="{$data.RecomendationLink}">{$data.RecomendationText}</a></option>
												{/if}
											{/foreach}
										</select>
									</td>
									<td class="s55"
									    dir="ltr"
									    colspan="4">
										<select name="iV44_{$data.iPos}">
											{foreach from=$vRecomendationV44 item=data name="rows"}
												{if $data.RecomendationLink == ""}
													<option value="{$data.RecomendationId}">{$data.RecomendationText}</option>
												{else}
													<option value="{$data.RecomendationId}"><a target="_blank" href="{$data.RecomendationLink}">{$data.RecomendationText}</a></option>
												{/if}
											{/foreach}
										</select>
									</td>
									<td class="s55"
									    dir="ltr"/>
									<td class="s40"/>
									<td class="s11"/>
									<td class="s11"/>
								</tr>
				{/foreach}
								<tr style="height: 20px">
									<th id="685479336R46"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s11"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s40"/>
									<td class="s60"
									    dir="ltr"/>
									<td class="s16"/>
									<td class="s16"/>
								</tr>
								<tr style="height: 20px">
									<th id="685479336R47"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s11"/>
									<td class="s61"/>
									<td class="s16"/>
									<td class="s16"/>
								</tr>
								<tr style="height: 20px">
									<th id="685479336R48"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s24"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s52"
									    dir="ltr"
									    colspan="6">Задания</td>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s43"/>
									<td class="s61"/>
									<td class="s16"/>
									<td class="s16"/>
								</tr>

								<tr style="height: 67px">
									<th id="685479336R49"
									    style="height: 67px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 67px"></div>
									</th>
									<td class="s62"/>
									<td class="s63"
									    dir="ltr">Название задания</td>
									<td class="s63"
									    dir="ltr"
									    colspan="11">Описание задания</td>
									<td class="s63"
									    dir="ltr"
									    colspan="6">Реквизиты</td>
									<td class="s64"
									    dir="ltr">Распечатки</td>
									<td class="s63"
									    dir="ltr">Карточки</td>
									<td class="s63"
									    dir="ltr"
									    colspan="3">Видео инструкция</td>
									<td class="s60"/>
									<td class="s16"/>
									<td class="s16"/>
								</tr>
						{foreach from=$vTasks item=data name="rows"}
								<tr style="height: 89px">
									<th id="685479336R50"
									    style="height: 89px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 89px"></div>
									</th>
									<td class="s65"
									    dir="ltr"
									    rowspan="2">{$data.iPos}</td>
									<td class="s66">{$data.JobName}</td>
									<td class="s8"
									    dir="ltr"
									    colspan="11"
									    rowspan="2">{$data.JobDescription}</td>
											<td class="s8"
											    dir="ltr">{$data.Rekvizit1Name}</td>
											<td class="s8"
											    dir="ltr">{$data.Rekvizit2Name}</td>
											<td class="s8"
											    dir="ltr">{$data.Rekvizit3Name}</td>
											<td class="s8"
											    dir="ltr">{$data.Rekvizit4Name}</td>
											<td class="s8"
											    dir="ltr">{$data.Rekvizit5Name}</td>
											<td class="s8"
											    dir="ltr">{$data.Rekvizit6Name}</td>
											<td class="s67"
											    dir="ltr"
											    rowspan="2">
											{if $data.JobPrintPDF != ""}
											<a target="_blank" href="{$data.JobPrintPDF}">ссылка</a>
											{/if}
											</td>
											<td class="s68"
											    dir="ltr"
											    rowspan="2">
											{if $data.JobCardPDF != ""}
											<a target="_blank" href="{$data.JobCardPDF}">ссылка</a>
											{/if}
											</td>
											<td class="s68"
											    dir="ltr"
											    colspan="3"
											    rowspan="2">
											{if $data.JobVideo != ""}
											<a target="_blank" href="{$data.JobVideo}">ссылка</a>
											{/if}
											</td>
											<td class="s61"/>
											<td class="s16"/>
											<td class="s16"/>
										</tr>
										<tr style="height: 16px">
											<th id="685479336R51"
											    style="height: 16px;"
											    class="row-headers-background">
												<div class="row-header-wrapper"
												     style="line-height: 16px"></div>
											</th>
											<td class="s69">{$data.JobCode}</td>
											<td class="s70"
											    dir="ltr">
											{if $data.Rekvizit1Link != ""}
											<a target="_blank" href="{$data.Rekvizit1Link}">ссылка</a>
											{/if}
											</td>
											<td class="s70"
											    dir="ltr">
											{if $data.Rekvizit3Link != ""}
											<a target="_blank" href="{$data.Rekvizit2Link}">ссылка</a>
											{/if}
											</td>
											<td class="s71"
											    dir="ltr">
													{if $data.Rekvizit3Link != ""}
													<a target="_blank" href="{$data.Rekvizit3Link}">ссылка</a>
													{/if}
												</td>
											<td class="s71"
											    dir="ltr">
													{if $data.Rekvizit4Link != ""}
													<a target="_blank" href="{$data.Rekvizit4Link}">ссылка</a>
													{/if}
												</td>
											<td class="s71"
											    dir="ltr">
													{if $data.Rekvizit5Link != ""}
													<a target="_blank" href="{$data.Rekvizit5Link}">ссылка</a>
													{/if}
												</td>
											<td class="s71"
											    dir="ltr">
													{if $data.Rekvizit6Link != ""}
													<a target="_blank" href="{$data.Rekvizit6Link}">ссылка</a>
													{/if}
												</td>
											<td class="s60"/>
											<td class="s16"/>
											<td class="s16"/>
										</tr>
								{/foreach}

																</tbody>
															</table>
														</div>



<input type=hidden name="Report" id="Report" value="0"/>

<input type=hidden name=csrf value='{$csrf}'/>