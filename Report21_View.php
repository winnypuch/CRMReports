{literal}
    <script type="text/javascript">
  //      $(function () {
  //          var $start = $('#sDateT1');

  //          $start.datepicker({
  //              maxDate: '+ 1 month',
  //              onSelect: function (selectedDate) {
		//			return SubmitData();
  //              }
  //          });
		//});
		//iAction 1 - установка рекомендации, 2-убрать рекомендацию
		function ChangeRecomendation(sRecId, iRecCount, iPos, iAction) {
			var vRecom = document.getElementById(sRecId + iPos);
			if (iAction == 1) {
				if (vRecom.value == '0') {
					if (iRecCount > 0) {
						if (iRecCount == 1) {
							vRecom.selectedIndex = 1;
						} else {
							vRecom.value = '-2';
						}
					} else {
						vRecom.value = '-1';
					}
				}
			} else {
				vRecom.value = '0';
			}
			ChangeFactRecomendation(sRecId + iPos);
		}

		function ChangeWhatDidLearn(sRecId, iRecCount, iPos, iAction) {
			var vRecom = document.getElementById(sRecId + iPos);
			if (iAction == 1) {
				if (vRecom.value == '0') {
					if (iRecCount > 0) {
						if (iRecCount == 1) {
							vRecom.selectedIndex = 1;
						} else {
							vRecom.value = '-2';
						}
					} else {
						vRecom.value = '-1';
					}
				}
			} else {
				vRecom.value = '0';
			}
			ChangeWhatDidLearnColor(sRecId + iPos);
		}


		function Validation(sValName, sRecId, iRecCount, iPos) {
			//debugger;
			if (sValName == 'iJ' || sValName == 'iL') {
				if (document.getElementById('iJ' + iPos).value == '' && document.getElementById('iL' + iPos).value == '') {
					ChangeWhatDidLearn('iJ28', iWhatDidLearnJ28Count, '', 2);
				} else {
					ChangeWhatDidLearn('iJ28', iWhatDidLearnJ28Count, '', 1);
				}
				if ((document.getElementById('iJ' + iPos).value == '' || document.getElementById('iJ' + iPos).value == '+')
					&& (document.getElementById('iL' + iPos).value == '' || document.getElementById('iL' + iPos).value == '+')) {
					ChangeRecomendation(sRecId, iRecCount, iPos, 2);
				} else {
					ChangeRecomendation(sRecId, iRecCount, iPos, 1);
				}
			}

			if (sValName == 'iN' || sValName == 'iP') {
				if (document.getElementById('iN' + iPos).value == '' && document.getElementById('iP' + iPos).value == '') {
					ChangeWhatDidLearn('iN28', iWhatDidLearnN28Count, '', 2);
				} else {
					ChangeWhatDidLearn('iN28', iWhatDidLearnN28Count, '', 1);
				}
				if ((document.getElementById('iN' + iPos).value == '' || document.getElementById('iN' + iPos).value == '+')
					&& (document.getElementById('iP' + iPos).value == '' || document.getElementById('iP' + iPos).value == '+')) {
					ChangeRecomendation(sRecId, iRecCount, iPos, 2);
				} else {
					ChangeRecomendation(sRecId, iRecCount, iPos, 1);
				}
			}

			if (sValName == 'iR' || sValName == 'iT') {
				if (document.getElementById('iR' + iPos).value == '' && document.getElementById('iT' + iPos).value == '') {
					ChangeWhatDidLearn('iR28', iWhatDidLearnR28Count, '', 2);
				} else {
					ChangeWhatDidLearn('iR28', iWhatDidLearnR28Count, '', 1);
				}
				if ((document.getElementById('iR' + iPos).value == '' || document.getElementById('iR' + iPos).value == '+')
					&& (document.getElementById('iT' + iPos).value == '' || document.getElementById('iT' + iPos).value == '+')) {
					ChangeRecomendation(sRecId, iRecCount, iPos, 2);
				} else {
					ChangeRecomendation(sRecId, iRecCount, iPos, 1);
				}
			}

			if (sValName == 'iV' || sValName == 'iX') {
				if (document.getElementById('iV' + iPos).value == '' && document.getElementById('iX' + iPos).value == '') {
					ChangeWhatDidLearn('iV28', iWhatDidLearnV28Count, '', 2);
				} else {
					ChangeWhatDidLearn('iV28', iWhatDidLearnV28Count, '', 1);
				}
				if ((document.getElementById('iV' + iPos).value == '' || document.getElementById('iV' + iPos).value == '+')
					&& (document.getElementById('iX' + iPos).value == '' || document.getElementById('iX' + iPos).value == '+')) {
					ChangeRecomendation(sRecId, iRecCount, iPos, 2);
				} else {
					ChangeRecomendation(sRecId, iRecCount, iPos, 1);
				}
			}

			//убираем н если есть оценки
			if (document.getElementById('iJ' + iPos).value == '' && document.getElementById('iL' + iPos).value == '' && document.getElementById('iN' + iPos).value == '' && document.getElementById('iP' + iPos).value == '' && document.getElementById('iR' + iPos).value == '' && document.getElementById('iT' + iPos).value == '' && document.getElementById('iV' + iPos).value == '' && document.getElementById('iX' + iPos).value == '') {
				document.getElementById('iI' + iPos).value = 'н';
			} else {
				document.getElementById('iI' + iPos).value = '';
			}
			CheckPay();
		}

		function ChangeWhatDidLearnColor(sRecomendation) {
			//debugger;
			var vRecom = document.getElementById(sRecomendation);
			var vRecomText = document.getElementById(sRecomendation + '_Text');
			vRecomText.value = vRecom.options[vRecom.selectedIndex].text
			switch (vRecom.value) {
				case '0':
				case '-1':
					vRecom.style.backgroundColor="white";
					break;
				case '-2':
					vRecom.style.backgroundColor="yellow";
					break;
				default:
					vRecom.style.backgroundColor="#93C47D";
            }
		}


		function ChangeFactRecomendation(sRecomendation) {
			//debugger;
			var vRecom = document.getElementById(sRecomendation);
			var vRecomText = document.getElementById(sRecomendation + '_Text');
			var vRecomLink = document.getElementById(sRecomendation + '_Link');
			if (vRecom.value == '0') {
				vRecomText.value = '';
				vRecomLink.innerHTML = '';
			} else {
				vRecomText.value = vRecom.options[vRecom.selectedIndex].dataset.reccode;

				if (vRecom.options[vRecom.selectedIndex].dataset.reclink != '') {
					vRecomLink.innerHTML = "<a target = \"_blank\" href = \"" + vRecom.options[vRecom.selectedIndex].dataset.reclink + "\">ссылка</a>";
				} else {
					vRecomLink.innerHTML = '';
				}
			}
			switch (vRecom.value) {
				case '0':
				case '-1':
					vRecom.style.backgroundColor="white";
					break;
				case '-2':
					vRecom.style.backgroundColor="yellow";
					break;
				default:
					vRecom.style.backgroundColor="#93C47D";
            }
		}

		function CheckPay() {
			if (document.getElementById("iPayFact_F28").value == '') {
				bIsPay = false;
				for (var i = 1; i <= iChildrensCount; i++) {
					if (document.getElementById('iJ' + i).value != '' || document.getElementById('iL' + i).value != ''
						|| document.getElementById('iN' + i).value != '' || document.getElementById('iP' + i).value != ''
						|| document.getElementById('iR' + i).value != '' || document.getElementById('iT' + i).value != ''
						|| document.getElementById('iV' + i).value != '' || document.getElementById('iX' + i).value != '') {
						bIsPay = true;
						break;
					}
				}
				let vPayPlan = document.getElementById("iPayPlan_F28");
				let vPayPlanText = document.getElementById("iPayPlan_F28_Text");
				if (bIsPay) {
					vPayPlan.value = 'Оплата';
					if (bIsAdmin) {
						vPayPlanText.textContent = 'Оплата';
						vPayPlanText.style.backgroundColor = "#93C47D";
					}
				} else {
					vPayPlan.value = 'Неоплата';
					if (bIsAdmin) {
						vPayPlanText.textContent = 'Неоплата';
						vPayPlanText.style.backgroundColor = "#FF0000";
					}
				}
			}
		}
		function ChangePay() {
			//debugger;
			let vPayFact = document.getElementById("iPayFact_F28");

			let vPayPlan = document.getElementById("iPayPlan_F28");
			let vPayPlanText = document.getElementById("iPayPlan_F28_Text");
			if (vPayFact.value == '') {
				CheckPay();
				vPayFact.style.backgroundColor = "white";
			} else {
				if (vPayFact.value == 'Оплата') {
					vPayPlan.value = 'Оплата';
					vPayPlanText.textContent = 'Оплата';
					vPayPlanText.style.backgroundColor = "#93C47D";
					vPayFact.style.backgroundColor = "#93C47D";
				} else {
					vPayPlan.value = 'Неоплата';
					vPayPlanText.textContent = 'Неоплата';
					vPayPlanText.style.backgroundColor = "#FF0000";
					vPayFact.style.backgroundColor = "#FF0000";
				}
			}
		}

		function CheckSave() {
			var href_post = confirm('Загрузить?');
			if (href_post) {
				var vGroup = document.getElementById('iGroupId');
				if (vGroup != null && vGroup.value != '0' && vGroup.value != '') {
					var bIsCheckRecomendation = true;
					var bIsCheckWhatDidLearnJ = false;
					var bIsCheckWhatDidLearnN = false;
					var bIsCheckWhatDidLearnR = false;
					var bIsCheckWhatDidLearnV = false;

					for (var i = 1; i <= iChildrensCount; i++) {
						if ((document.getElementById('iJ' + i).value != '' || document.getElementById('iL' + i).value != '')) {
							bIsCheckWhatDidLearnJ = true;
							if (!(document.getElementById('iJ44_' + i).value == -1 || document.getElementById('iJ44_' + i).value > 0)) {
								bIsCheckRecomendation = false;
							}
						}
						if ((document.getElementById('iN' + i).value != '' || document.getElementById('iP' + i).value != '')) {
							bIsCheckWhatDidLearnN = true;
							if (!(document.getElementById('iN44_' + i).value == -1 || document.getElementById('iN44_' + i).value > 0)) {
								bIsCheckRecomendation = false;
							}
						}
						if ((document.getElementById('iR' + i).value != '' || document.getElementById('iT' + i).value != '')) {
							bIsCheckWhatDidLearnR = true;
							if (!(document.getElementById('iR44_' + i).value == -1 || document.getElementById('iR44_' + i).value > 0)) {
								bIsCheckRecomendation = false;
							}
						}
						if ((document.getElementById('iV' + i).value != '' || document.getElementById('iX' + i).value != '')) {
							bIsCheckWhatDidLearnV = true;
							if (!(document.getElementById('iV44_' + i).value == -1 || document.getElementById('iV44_' + i).value > 0)) {
								bIsCheckRecomendation = false;
							}
						}
					}
					if ((!bIsCheckWhatDidLearnJ || (bIsCheckWhatDidLearnJ && (document.getElementById('iJ28').value == -1 || document.getElementById('iJ28').value > 0)))
						&& (!bIsCheckWhatDidLearnN || (bIsCheckWhatDidLearnN && (document.getElementById('iN28').value == -1 || document.getElementById('iN28').value > 0)))
						&& (!bIsCheckWhatDidLearnR || (bIsCheckWhatDidLearnR && (document.getElementById('iR28').value == -1 || document.getElementById('iR28').value > 0)))
						&& (!bIsCheckWhatDidLearnV || (bIsCheckWhatDidLearnV && (document.getElementById('iV28').value == -1 || document.getElementById('iV28').value > 0)))
					) {
						if (bIsCheckRecomendation) {
							var bIsSave = true;
							if (document.getElementById('upload1').value == "" && document.getElementById('upload2').value == "") {
								bIsSave = false;
							}
							if (!bIsSave && confirm('Не прикреплено ни одной фотографии. Вы уверены, что хотите загрузить отчет?'))
								bIsSave = true;
							if (bIsSave) {
								document.getElementById('SaveReport').value = '1';
								document.getElementById('report_form').submit();
								document.getElementById('SaveReport').value = '0';
							}
						} else {
							alert('Выберете рекомендацию!');
						}
					} else {
						alert('Не заполнены поля “Чему учились”!');
					}
				} else {
					alert('Укажите группу!');
				}
			}

		}
		//0-- только группа
		//1-- группа и Формат образования
		//2-- группа и другой день недели
		function SubmitData(iType) {
            //document.getElementById('Report').value = '0';
            //document.getElementById('SendReport').value = '0';
            //document.getElementById('ChildrenId').value = '0'
			if (iType == 0) {
				document.getElementById('FormaFact').value = '0';
				document.getElementById('WeekLesson').value = '0';
			} else {
				if (iType == 1)
					document.getElementById('FormaFact').value = '1';
				if (iType == 2)
					document.getElementById('WeekLesson').value = '1';
			}
            document.getElementById('report_form').submit();
            return false;
        }
		$(function () {
			ChangeWeekLesson = $("#ChangeWeekLesson");
			document.getElementById('report_form').enctype = "multipart/form-data";
			vWeekLessonDialog = $("#WeekLessonDialog").dialog({
				autoOpen: false,
				title: "Укажите неделю.урок",
				resizable: false,
				closeText: 'Отмена',
				modal: true,
				buttons: {
					"Изменить": function () {
						if (/^\d{1,2}\.\d{1}$/.test(ChangeWeekLesson.val())) {
							document.getElementById('sWeekLessonR2').value = ChangeWeekLesson.val();
							SubmitData(2);
						} else {
							alert('Укажите значение в формате (номер недели.номер урока)');
						}
					},
					"Отмена": function () {
						vWeekLessonDialog.dialog("close");
					}
				},
			});
			$("#WeekLessonSelect").on("click", function() {
				vWeekLessonDialog.dialog("open");
				ChangeWeekLesson.val(document.getElementById('sWeekLessonR2').value);
			});
		});
    </script>
<style type="text/css">
	.fullproc {
		width: 87%;
		padding: 2px;
		max-width: 100%;
		white-space: pre-wrap;
	}
	.full100 {
		width: 100%;
		padding: 1px;
		height:100%;
	}
	.yellowbk{
		background-color:#FFFF00 !important;
	}
	.redbk{
		background-color:#FF0000 !important;
	}
	.greenbk{
		background-color:#B6D7A8 !important;
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
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
		border-left: 1px SOLID #000000;
		border-top: 1px SOLID #000000;
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

	.ritz .waffle .s68 {
		border-bottom: 1px SOLID #000000;
		border-right: 1px SOLID #000000;
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

		text-align: center;
		font-style: italic;
		color: #000000;
		font-family: 'Times New Roman';
		font-size: 10pt;
		vertical-align: middle;
		white-space:  normal;
		word-wrap: break-word;
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
		border-right: 1px SOLID #000000;
		border-left: 1px SOLID #000000;
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
		border-left: 1px SOLID #000000;
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
		vertical-align: middle;
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
		vertical-align: middle;
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
		border-left: 1px SOLID #000000;

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
		vertical-align: middle;
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
		background-color: #ffffff;
		text-align: left;
		color: #000000;
		font-family: 'Arial';
		font-size: 9pt;
		vertical-align: middle;
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
	<div id="WeekLessonDialog" title="Выбор новой Недели/урока">
		<p><input id="ChangeWeekLesson" type="text"></p>
	</div>

<script type="text/javascript">
	let iChildrensCount = {$iChildrensCount};
	let iWhatDidLearnJ28Count = {$iWhatDidLearnJ28Count};
	let iWhatDidLearnN28Count = {$iWhatDidLearnN28Count};
	let iWhatDidLearnR28Count = {$iWhatDidLearnR28Count};
	let iWhatDidLearnV28Count = {$iWhatDidLearnV28Count};
	let bIsAdmin = {if $bIsAdmin}true{else}false{/if};
</script>
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
                <input type="button" value="Обновить" class="no_print btn btn-default btn-sm"
                       onclick="return SubmitData(0);"/>
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
				<input type="button" value="Загрузить" class="no_print btn btn-default btn-sm" onclick="return CheckSave();"/>
            </td>
        </tr>
    </table>
</div>
		<div class="ritz  mainTable"
		     dir="ltr">
			<table class="waffle mainTable"
			       cellspacing="0"
			       cellpadding="0">
				<tbody>
					<tr style="height:0px;">
						<th />
						<th style="width:22px;" class="column-headers-background"/>
						<th style="width:230px;" class="column-headers-background"/>
						<th style="width:80px;" class="column-headers-background"/>
						<th style="width:140px;" class="column-headers-background"/>
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
                <select class="fullproc" id="iGroupId" name="iGroupId" onchange="return  SubmitData(0);">
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
								<select class="fullproc" name="sFormatFactO1" onchange="return  SubmitData(1);">
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
						    colspan="4">Информация по занятию<input type=hidden name="sDateTimeT1" id="sDateTimeT1" value="{$sDateTimeT1}"/></td>
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
					</tr>
					<tr style="height: 20px">
						<th id="685479336R1"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s7"
						    dir="ltr"
						    colspan="2">Преподаватель 1<input id="iTeacherFioFactId" name="iTeacherFioFactId" type="hidden" value="{$iTeacherFioFactId}"/></td>
						<td class="s7"
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
						    dir="ltr">{$sWeekLessonR2}
							<input type=hidden name="sWeekLessonR2" id="sWeekLessonR2" value="{$sWeekLessonR2}"/></td>
						<td class="s14"
						    dir="ltr"><a href="#" id="WeekLessonSelect" title="Изменить Недлю/урок">{$sWeekLessonR2}</a></td>
						<td class="s0"
						    dir="ltr">Учебный год<input type=hidden name="sAcademicYearU2" id="sAcademicYearU2" value="{$sAcademicYearU2}"/></td>
						<td class="s1">{$sAcademicYearU2}</td>
						<td class="s9"
						    dir="ltr"
						    colspan="2">Возраст<input type=hidden name="sProgramAgeX2" id="sProgramAgeX2" value="{$sProgramAgeX2}"/></td>
						<td class="s9"
						    dir="ltr"
						    colspan="2">{$sProgramAgeX2}</td>
						<td class="s6"/>
						<td class="s11"/>
					</tr>
					<tr style="height: 20px">
						<th id="685479336R2"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s7"
						    dir="ltr"
						    colspan="2">Преподаватель 2</td>
						<td class="s8"
						    dir="ltr"
						    colspan="3">
							{if $bIsAdmin}
								<select class="fullproc" id="iTeacherFioFactId2" name="iTeacherFioFactId2">
									{foreach from=$vTeachers item=data name="rows"}
										<option value="{$data.TeacherId}">{$data.TeacherName}</option>
									{/foreach}
								</select>
							{/if}
						</td>
						<td class="s7"
						    dir="ltr"
						    colspan="2">ДЗ<input type=hidden name="sProgramForYearL3" id="sProgramForYearL3" value="{$sProgramForYearL3}"/></td>
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
						    rowspan="5"><textarea style="width:234px;height:168px;" name="CommentNextClass"></textarea></td>
						<td class="s9"
						    dir="ltr"
						    colspan="3">Комментарий по уровню темы</td>
						<td class="s18"
						    dir="ltr"
						    colspan="4"><textarea class="full100" name="CommentTopicLevelJ4"></textarea></td>
						<td class="s19"
						    dir="ltr"
						    colspan="4"><textarea class="full100" name="CommentTopicLevelN4"></textarea></td>
						<td class="s19"
						    dir="ltr"
						    colspan="4"><textarea class="full100" name="CommentTopicLevelR4"></textarea></td>
						<td class="s19"
						    dir="ltr"
						    colspan="4"><textarea class="full100" name="CommentTopicLevelV4"></textarea></td>
						<td class="s15"
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
						    colspan="3"><textarea class="full100" name="CommentTaskLevelJ5"></textarea></td>
						<td class="s13{if $sM5=='нужно'} yellowbk{/if}"
						    dir="ltr">{$sM5}</td>
						<td class="s19"
						    dir="ltr"
						    colspan="3"><textarea class="full100" name="CommentTaskLevelN5"></textarea></td>
						<td class="s13{if $sQ5=='нужно'} yellowbk{/if}"
						    dir="ltr">{$sQ5}</td>
						<td class="s19"
						    dir="ltr"
						    colspan="3"><textarea class="full100" name="CommentTaskLevelR5"></textarea></td>
						<td class="s13{if $sU5=='нужно'} yellowbk{/if}"
						    dir="ltr">{$sU5}</td>
						<td class="s19"
						    dir="ltr"
						    colspan="3"><textarea class="full100" name="CommentTaskLevelV5"></textarea></td>
						<td class="s13{if $sY5=='нужно'} yellowbk{/if}"
						    dir="ltr">{$sY5}</td>
						<td class="s21"
						    dir="ltr"/>
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
						    colspan="4"><input type=hidden name="sTopicJ8" id="sTopicJ8" value="{$sTopicJ8}"/>{$sTopicJ8}</td>
						<td class="s12"
						    colspan="4"><input type=hidden name="sTopicN8" id="sTopicN8" value="{$sTopicN8}"/>{$sTopicN8}</td>
						<td class="s12"
						    colspan="4"><input type=hidden name="sTopicR8" id="sTopicR8" value="{$sTopicR8}"/>{$sTopicR8}</td>
						<td class="s12"
						    colspan="4"><input type=hidden name="sTopicV8" id="sTopicV8" value="{$sTopicV8}"/>{$sTopicV8}</td>
						<td class="s23"/>
						<td class="s24"
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
					</tr>
					<tr style="height: 20px">
						<th id="685479336R9"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
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
						    colspan="4">Код задания {$sJobCodeJ11}<input type=hidden name="sJobCodeJ11" id="sJobCodeJ11" value="{$sJobCodeJ11}"/></td>
						<td class="s12"
						    dir="ltr"
						    colspan="4">Код задания {$sJobCodeN11}<input type=hidden name="sJobCodeN11" id="sJobCodeN11" value="{$sJobCodeN11}"/></td>
						<td class="s12"
						    dir="ltr"
						    colspan="4">Код задания {$sJobCodeR11}<input type=hidden name="sJobCodeR11" id="sJobCodeR11" value="{$sJobCodeR11}"/></td>
						<td class="s12"
						    dir="ltr"
						    colspan="4">Код задания {$sJobCodeV11}<input type=hidden name="sJobCodeV11" id="sJobCodeV11" value="{$sJobCodeV11}"/></td>
					</tr>
				{foreach from=$lines item=data name="rows"}
					<tr style="height: 20px">
						<th id="685479336R11"
						    style="height: 20px;"
						    class="row-headers-background">
						</th>
						<td class="s27{if $data.iPosn=='н'} redbk{/if}"
						    dir="ltr">{$data.iPosn}<input id="iB{$data.iPos}" name="iB{$data.iPos}" type="hidden" value="{$data.ChildrenId}"/></td>
						<td class="s28"
						    dir="ltr">{$data.ChildrenFIO}</td>
						<td class="s12"
						    dir="ltr">{$data.ClassGroup}</td>
						<td class="s12">{$data.PickGivesName}</td>
						<td class="s12" dir="ltr">{if $bIsAdmin}{$data.Stars - $data.Gifts}{/if}</td>
						<td class="s12" dir="ltr" style="width:40px">
							{if $bIsAdmin}
							<input id="iG{$data.iPos}" style="width:30px" name="iG{$data.iPos}" type="number" value=""/>
							{else}
							<input id="iG{$data.iPos}" style="width:30px" name="iG{$data.iPos}" type="hidden" value=""/>
							{/if}
						</td>
						<td class="s29">
							<select name="iH{$data.iPos}">
								{if $data.Trial=='пробное'}
									<option value=""></option>
									<option value="пробное" selected style="background-color:green;">пробное</option>
								{else}
									<option value="" selected></option>
									<option value="пробное" style="background-color:green;">пробное</option>
								{/if}
							</select>
							/{$data.WorkingOff}
							<input type=hidden name="iHH{$data.iPos}" id="iHH{$data.iPos}" value="{$data.WorkingOff}"/>
						</td>
						<td class="s12" dir="ltr">
							<select id="iI{$data.iPos}" name="iI{$data.iPos}" class="fullproc">
								<option value="н" selected>н</option>
								<option value=""></option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select class="fullproc" id="iJ{$data.iPos}" name="iJ{$data.iPos}" onchange="return Validation('iJ','iJ44_',{$iRecomendationJ44Count},{$data.iPos})">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select class="fullproc" id="iL{$data.iPos}" name="iL{$data.iPos}" onchange="return Validation('iL','iJ44_',{$iRecomendationJ44Count},{$data.iPos})">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select class="fullproc" id="iN{$data.iPos}" name="iN{$data.iPos}" onchange="return Validation('iN','iN44_',{$iRecomendationN44Count},{$data.iPos})">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select class="fullproc" id="iP{$data.iPos}" name="iP{$data.iPos}" onchange="return Validation('iP','iN44_',{$iRecomendationN44Count},{$data.iPos})">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select class="fullproc" id="iR{$data.iPos}" name="iR{$data.iPos}" onchange="return Validation('iR','iR44_',{$iRecomendationR44Count},{$data.iPos})">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select class="fullproc" id="iT{$data.iPos}" name="iT{$data.iPos}" onchange="return Validation('iT','iR44_',{$iRecomendationR44Count},{$data.iPos})">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select class="fullproc" id="iV{$data.iPos}" name="iV{$data.iPos}" onchange="return Validation('iV','iV44_',{$iRecomendationV44Count},{$data.iPos})">
								<option value="" selected></option>
								<option value="+">+</option>
								<option value="+-">+-</option>
								<option value="-">-</option>
							</select>
						</td>
						<td class="s12"
						    dir="ltr"
						    colspan="2">
							<select class="fullproc" id="iX{$data.iPos}" name="iX{$data.iPos}" onchange="return Validation('iX','iV44_',{$iRecomendationV44Count},{$data.iPos})">
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
								<select name="iAI{$data.iPos}">
									{foreach from=$vGifts item=data name="rows"}
										<option value="{$data.GiftName}">{$data.GiftName}</option>
									{/foreach}
								</select>
							{/if}
						</td>
					</tr>
				{/foreach}
					<tr style="height: 70px">
						<th id="685479336R27"
						    style="height: 70px;"
						    class="row-headers-background">
						</th>
						<td class="s11"/>
						<td class="s32"><input id="iPayPlan_F28" name="iPayPlan_F28" type="hidden" value="Неоплата"/></td>
						<td class="s1"
						    dir="ltr"
						    colspan="2" id="iPayPlan_F28_Text" {if $bIsAdmin}style="background-color:#FF0000;"{/if}>{if $bIsAdmin}Неоплата{/if}</td>

						<td class="s1"
						    dir="ltr"
						    colspan="2">
							{if $bIsAdmin}
							<select class="fullproc" name="iPayFact_F28" id="iPayFact_F28" onchange="ChangePay()">
								<option value="" selected></option>
								<option value="Оплата" class="greenbk">Оплата</option>
								<option value="Неоплата" class="redbk">Неоплата</option>
							</select>
							{else}
							<input id="iPayFact_F28" name="iPayFact_F28" type="hidden" value=""/>
							{/if}
						</td>
						<td class="s34"
						    dir="ltr"
						    colspan="2">
						Чему учились?
						</td>
						<td class="s17"
						    dir="ltr"
						    colspan="4">
								<input type=hidden name="iJ28_Text" id="iJ28_Text" value=""/>
								<select class="fullproc" id="iJ28" name="iJ28" onchange="ChangeWhatDidLearnColor('iJ28')">
									<option value="0"></option>
									{if $iWhatDidLearnJ28Count > 0}
										{if $iWhatDidLearnJ28Count > 1}
											<option value="-2" style="background-color:yellow">Выберите текст</option>
										{/if}
										{foreach from=$vWhatDidLearnJ28 item=data name="rows"}
											<option value="{$data.WhatDidLearnId}">{$data.WhatDidLearnName}</option>
										{/foreach}
									{else}
										<option value="-1">Нет данных в базе</option>
									{/if}
								</select>
						</td>
						<td class="s17"
						    dir="ltr"
						    colspan="4">
								<input type=hidden name="iN28_Text" id="iN28_Text" value=""/>
								<select class="fullproc" id="iN28" name="iN28" onchange="ChangeWhatDidLearnColor('iN28')">
									<option value="0"></option>
									{if $iWhatDidLearnN28Count > 0}
										{if $iWhatDidLearnN28Count > 1}
											<option value="-2" style="background-color:yellow">Выберите текст</option>
										{/if}
										{foreach from=$vWhatDidLearnN28 item=data name="rows"}
											<option value="{$data.WhatDidLearnId}">{$data.WhatDidLearnName}</option>
										{/foreach}
									{else}
										<option value="-1">Нет данных в базе</option>
									{/if}
								</select>
						</td>
						<td class="s17"
						    dir="ltr"
						    colspan="4">
								<input type=hidden name="iR28_Text" id="iR28_Text" value=""/>
								<select class="fullproc" id="iR28" name="iR28" onchange="ChangeWhatDidLearnColor('iR28')">
									<option value="0"></option>
									{if $iWhatDidLearnR28Count > 0}
										{if $iWhatDidLearnR28Count > 1}
											<option value="-2" style="background-color:yellow">Выберите текст</option>
										{/if}
										{foreach from=$vWhatDidLearnR28 item=data name="rows"}
											<option value="{$data.WhatDidLearnId}">{$data.WhatDidLearnName}</option>
										{/foreach}
									{else}
										<option value="-1">Нет данных в базе</option>
									{/if}
								</select>
						</td>
						<td class="s17"
						    dir="ltr"
						    colspan="4">
								<input type=hidden name="iV28_Text" id="iV28_Text" value=""/>
								<select class="fullproc" id="iV28" name="iV28" onchange="ChangeWhatDidLearnColor('iV28')">
									<option value="0"></option>
									{if $iWhatDidLearnV28Count > 0}
										{if $iWhatDidLearnV28Count > 1}
											<option value="-2" style="background-color:yellow">Выберите текст</option>
										{/if}
										{foreach from=$vWhatDidLearnV28 item=data name="rows"}
											<option value="{$data.WhatDidLearnId}">{$data.WhatDidLearnName}</option>
										{/foreach}
									{else}
										<option value="-1">Нет данных в базе</option>
									{/if}
								</select>
						</td>
						<td class="s36"
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
						    colspan="4"><textarea class="full100" name="NotesJ31"></textarea></td>
						<td class="s42"
						    dir="ltr"
						    colspan="4"><textarea class="full100" name="NotesN31"></textarea></td>
						<td class="s42"
						    dir="ltr"
						    colspan="4"><textarea class="full100" name="NotesR31"></textarea></td>
						<td class="s42"
						    dir="ltr"
						    colspan="4"><textarea class="full100" name="NotesV31"></textarea></td>
						<td class="s40"/>
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
						<td class="s3{if $sPreviosCommentJ33!=''} greenbk{/if}"
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
							<td class="s3{if $sThemeJ34!=''} greenbk{/if}"
							    colspan="4">{$sThemeJ34}</td>
							<td class="s3{if $sThemeN34!=''} greenbk{/if}"
							    dir="ltr"
							    colspan="4">{$sThemeN34}</td>
								<td class="s3{if $sThemeR34!=''} greenbk{/if}"
								    colspan="4">{$sThemeR34}</td>
								<td class="s3{if $sThemeV34!=''} greenbk{/if}"
								    colspan="4">{$sThemeV34}</td>
								<td class="s11"/>
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
								<td class="s3{if $sTaskJ35!=''} greenbk{/if}"
								    colspan="4">{$sTaskJ35}</td>
								<td class="s3{if $sTaskN35!=''} greenbk{/if}"
								    colspan="4">{$sTaskN35}</td>
								<td class="s3{if $sTaskR35!=''} greenbk{/if}"
								    colspan="4">{$sTaskR35}</td>
								<td class="s3{if $sTaskV35!=''} greenbk{/if}"
								    dir="ltr"
								    colspan="4">{$sTaskV35}</td>
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
								</tr>
				{foreach from=$lines item=data name="rows"}
							<tr style="height: 20px">
									<th id="685479336R43"
									    style="height: 20px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 20px"></div>
									</th>
									<td class="s53" colspan="2"/>
									<td class="s54{if $data.iPosn=='н'} redbk{/if}">{$data.iPosn}</td>
									<td class="s29"
									    colspan="5">{$data.ChildrenFIO}</td>
									<td class="s55"
									    dir="ltr"
									    colspan="3">
										<input type=hidden name="iJ44_{$data.iPos}_Text" id="iJ44_{$data.iPos}_Text" value=""/>
										<select class="fullproc" id="iJ44_{$data.iPos}" name="iJ44_{$data.iPos}" onchange="ChangeFactRecomendation('iJ44_{$data.iPos}')">
											<option value="0"></option>
											{if $iRecomendationJ44Count > 0}
												{if $iRecomendationJ44Count > 1}
													<option value="-2" style="background-color:yellow">Выберите рекомендацию</option>
												{/if}
												{foreach from=$vRecomendationJ44 item=data name="rows"}
													<option data-reclink="{$data.RecomendationLink}" data-reccode="{$data.RecomendationCode}" value="{$data.RecomendationId}">{$data.RecomendationText}</option>
												{/foreach}
											{else}
												<option value="-1">Нет рекомендации в базе</option>
											{/if}
										</select>
									</td>
									<td class="s68"
										dir="ltr"
										id="iJ44_{$data.iPos}_Link"></td>
									<td class="s55"
									    dir="ltr"
									    colspan="3">
										<input type=hidden name="iN44_{$data.iPos}_Text" id="iN44_{$data.iPos}_Text" value=""/>
										<select class="fullproc" id="iN44_{$data.iPos}" name="iN44_{$data.iPos}" onchange="ChangeFactRecomendation('iN44_{$data.iPos}')">
											<option value="0"></option>
											{if $iRecomendationN44Count > 0}
												{if $iRecomendationN44Count > 1}
													<option value="-2" style="background-color:yellow">Выберите рекомендацию</option>
												{/if}
												{foreach from=$vRecomendationN44 item=data name="rows"}
													<option data-reclink="{$data.RecomendationLink}" data-reccode="{$data.RecomendationCode}" value="{$data.RecomendationId}">{$data.RecomendationText}</option>
												{/foreach}
											{else}
												<option value="-1">Нет рекомендации в базе</option>
											{/if}
										</select>
									</td>
									<td class="s68"
										dir="ltr"
										id="iN44_{$data.iPos}_Link"></td>
									<td class="s55"
									    dir="ltr"
									    colspan="3">
										<input type=hidden name="iR44_{$data.iPos}_Text" id="iR44_{$data.iPos}_Text" value=""/>
										<select class="fullproc" id="iR44_{$data.iPos}" name="iR44_{$data.iPos}" onchange="ChangeFactRecomendation('iR44_{$data.iPos}')">
											<option value="0"></option>
											{if $iRecomendationR44Count > 0}
												{if $iRecomendationR44Count > 1}
													<option value="-2" style="background-color:yellow">Выберите рекомендацию</option>
												{/if}
												{foreach from=$vRecomendationR44 item=data name="rows"}
													<option data-reclink="{$data.RecomendationLink}" data-reccode="{$data.RecomendationCode}" value="{$data.RecomendationId}">{$data.RecomendationText}</option>
												{/foreach}
											{else}
												<option value="-1">Нет рекомендации в базе</option>
											{/if}
										</select>
									</td>
									<td class="s68"
										dir="ltr"
										id="iR44_{$data.iPos}_Link"></td>
									<td class="s55"
									    dir="ltr"
									    colspan="3">
										<input type=hidden name="iV44_{$data.iPos}_Text" id="iV44_{$data.iPos}_Text" value=""/>
										<select class="fullproc" id="iV44_{$data.iPos}" name="iV44_{$data.iPos}" onchange="ChangeFactRecomendation('iV44_{$data.iPos}')">
											<option value="0"></option>
											{if $iRecomendationV44Count > 0}
												{if $iRecomendationV44Count > 1}
													<option value="-2" style="background-color:yellow">Выберите рекомендацию</option>
												{/if}
												{foreach from=$vRecomendationV44 item=data name="rows"}
													<option data-reclink="{$data.RecomendationLink}" data-reccode="{$data.RecomendationCode}" value="{$data.RecomendationId}">{$data.RecomendationText}</option>
												{/foreach}
											{else}
												<option value="-1">Нет рекомендации в базе</option>
											{/if}
										</select>
									</td>
									<td class="s68"
										dir="ltr"
										id="iV44_{$data.iPos}_Link"></td>
									<td class="s11"
									    dir="ltr"/>
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
								</tr>

								<tr style="height: 30px">
									<th id="685479336R49"
									    style="height: 30px;"
									    class="row-headers-background">
										<div class="row-header-wrapper"
										     style="line-height: 30px"></div>
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
											    dir="ltr">{$data.JobRekvizit1Name}</td>
											<td class="s8"
											    dir="ltr">{$data.JobRekvizit2Name}</td>
											<td class="s8"
											    dir="ltr">{$data.JobRekvizit3Name}</td>
											<td class="s8"
											    dir="ltr">{$data.JobRekvizit4Name}</td>
											<td class="s8"
											    dir="ltr">{$data.JobRekvizit5Name}</td>
											<td class="s8"
											    dir="ltr">{$data.JobRekvizit6Name}</td>
											<td class="s68"
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
											{if $data.JobRekvizit1Link != ""}
											<a target="_blank" href="{$data.JobRekvizit1Link}">ссылка</a>
											{/if}
											</td>
											<td class="s70"
											    dir="ltr">
											{if $data.JobRekvizit2Link != ""}
											<a target="_blank" href="{$data.JobRekvizit2Link}">ссылка</a>
											{/if}
											</td>
											<td class="s70"
											    dir="ltr">
													{if $data.JobRekvizit3Link != ""}
													<a target="_blank" href="{$data.JobRekvizit3Link}">ссылка</a>
													{/if}
												</td>
											<td class="s70"
											    dir="ltr">
													{if $data.JobRekvizit4Link != ""}
													<a target="_blank" href="{$data.JobRekvizit4Link}">ссылка</a>
													{/if}
												</td>
											<td class="s70"
											    dir="ltr">
													{if $data.JobRekvizit5Link != ""}
													<a target="_blank" href="{$data.JobRekvizit5Link}">ссылка</a>
													{/if}
												</td>
											<td class="s70"
											    dir="ltr">
													{if $data.JobRekvizit6Link != ""}
													<a target="_blank" href="{$data.JobRekvizit6Link}">ссылка</a>
													{/if}
												</td>
											<td class="s60"/>
											<td class="s16"/>
										</tr>
								{/foreach}

									</tbody>
								</table>
							</div>
<input type=hidden name="SaveReport" id="SaveReport" value="0"/>
<input type=hidden name="FormaFact" id="FormaFact" value="{$FormaFact}"/>
<input type=hidden name="WeekLesson" id="WeekLesson" value="{$WeekLesson}"/>
<input type=hidden name="iChildrensCount" id="iChildrensCount" value="{$iChildrensCount}"/>
<input type=hidden name=csrf value='{$csrf}'/>