/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

$(document).ready(function () {
    $("#dashboardAdminTree").jstree({
	types : {
	    max_depth : -2,
	    max_children : -2,
	    types : {
			folder : {
			    icon : {
				image : "/img/icons/file.png"
			    }
			},
			group : {
			    icon : {
				image : "/img/icons/group.png"
			    }
			},
			user : {
			    icon : {
				image : "/img/icons/user.png"
			    }
			},
			queue : {
			    icon : {
				image : "/img/icons/queue.png"
			    }
			},
			settings : {
			    icon : {
				image : "/img/icons/settings.png"
			    }
			},
			settings_1 : {
			    icon : {
				image : "/img/icons/settings_1.png"
			    }
			},
			storage : {
			    icon : {
				image : "/img/icons/file-cab.png"
			    }
			},
			trash : {
			    icon : {
				image : "/img/icons/trash.png"
			    }
			},
			archive : {
			    icon : {
				image : "/img/icons/archive.png"
			    }
			},
			scan : {
			    icon : {
				image : "/img/icons/scanner.png"
			    }
			},
	        website : {
	            icon : {
	                image : "/img/icons/website.png"
	            }
	        },
	        pages : {
	            icon : {
	                image : "/img/icons/pages.png"
	            }
	        },
	        navigation : {
	            icon : {
	                image : "/img/icons/nav.png"
	            }
	        },
	        pressReleases: {
	            icon : {
	                image : "/img/icons/press_releases.png"
	            }
	        },
	        chairmanReports: {
	            icon : {
	                image : "/img/icons/chairman_reports.png"
	            }
	        },
	        loginIssues: {
	        	icon : {
	        		image: "/img/icons/application_key.png"
	        	}
	        },
	        tools :{
	        	icon : {
	        		image: "/img/icons/settings.png"
	        	}
	        },
	        hotJobs: {
	        	icon: {
	        		image: "/img/icons/hot_jobs.png"
	        	}
	        },
	        rfp: {
	        	icon: {
	        		image: "/img/icons/rfps.png"
	        	}
	        },
	        locations: {
	        	icon: {
	        		image: "/img/icons/location.png"
	        	}
	        },
	        calendar: {
	        	icon: {
	        		image: "/img/icons/calendar.png"
	        	}
	        },
	        featured: {
	        	icon: {
	        		image: "/img/icons/featured.png"
	        	}
	        },
	        inTheNews: {
	        	icon: {
	        		image: "/img/icons/news.png"
	        	}
	        },
	        surveys: {
	        	icon: {
	        		image: "/img/icons/survey.png"
	        	}
			}, careerSeekersSurveys: {
				icon: {
					image: '/img/icons/survey.png'
				}
			}, employersSurveys: {
				icon: {
					image: '/img/icons/survey.png'
				}
			},
	        featured: {
	        	icon: {
	        		image: "/img/icons/featured.png"
	        	}
	        },
	       	programs: {
	        	icon: {
	        		image: "/img/icons/clipboard.png"
	        	}
	        },
			selfSignSurvey: {
				icon: {
					image: "/img/icons/survey.png"
				}
			},
			alerts: {
				icon: {
					image: "/img/icons/email.png"
				}
			}
			reports: {
				icon: {
					image: '/img/icons/reports.png'
				}
			}
	    }
	},
	cookies: {
		save_selected: false,
		save_opened: 'dash_tree_open',
		cookie_options: {
		    expires: 7,
		    path: '/',
		    domain: domain,
		    secure: false
		}
	},
	plugins : [ "themes", "html_data", "types", "cookies" ]
    });
    $('#expand').toggle(function(){
	$.jstree._reference("#dashboardAdminTree").open_all();
	$(this).html('Collapse All')
    },
    function(){
	$.jstree._reference("#dashboardAdminTree").close_all();
	$(this).html('Expand All')
    })
});
