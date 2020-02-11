/**
 * Hypervote Namespane
 */
var Hypervote = {}

/**
 * Hypervote Schedule Form
 */
Hypervote.ScheduleForm = function () {
  var $form = $('.js-hypervote-schedule-form')
  var $searchinp = $form.find(":input[name='search']")
  var query
  var icons = {}
  icons.people_follower = 'mdi mdi-instagram'
  icons.people_getliker = 'mdi mdi-instagram'
  var target = []

  // Get ready tags
  $form.find('.tag').each(function () {
    target.push($(this).data('type') + '-' + $(this).data('id'))
  })

  // Search auto complete for targeting
  $searchinp.devbridgeAutocomplete({
    serviceUrl: $searchinp.data('url'),
    type: 'GET',
    dataType: 'jsonp',
    minChars: 2,
    deferRequestBy: 200,
    appendTo: $form,
    forceFixPosition: true,
    paramName: 'q',
    params: {
      action: 'search',
      type: $form.find(":input[name='type']:checked").val()
    },
    onSearchStart: function () {
      $form.find('.js-search-loading-icon').removeClass('none')
      query = $searchinp.val()
    },
    onSearchComplete: function () {
      $form.find('.js-search-loading-icon').addClass('none')
    },

    transformResult: function (resp) {
      return {
        suggestions: resp.result == 1 ? resp.items : []
      }
    },

    beforeRender: function (container, suggestions) {
      for (var i = 0; i < suggestions.length; i++) {
        var type = $form.find(":input[name='type']:checked").val()
        if (target.indexOf(type + '-' + suggestions[i].data.id) >= 0) {
          container.find('.autocomplete-suggestion').eq(i).addClass('none')
        }
      }
    },

    formatResult: function (suggestion, currentValue) {
      var pattern = '(' + currentValue.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, '\\$&') + ')'
      var type = $form.find(":input[name='type']:checked").val()

      return (suggestion.data.img ? "<img src='" + suggestion.data.img + "' style='width: 40px;height: 40px;margin: 0 12px 0 0; border-radius: 50%;float:left;border: 1px solid #e6e6e6;'>" : '') + suggestion.value
        .replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/&lt;(\/?strong)&gt;/g, '<$1>') +
                    (suggestion.data.sub ? "<span class='sub'>" + suggestion.data.sub + '<span>' : '')
    },

    onSelect: function (suggestion) {
      $searchinp.val(query)
      var type = $form.find(":input[name='type']:checked").val()

      if (target.indexOf(type + '-' + suggestion.data.id) >= 0) {
        return false
      }

      var $tag = $("<span style='margin: 0px 2px 3px 0px'></span>")
      $tag.addClass('tag pull-left preadd')
      $tag.attr({
        'data-type': type,
        'data-id': suggestion.data.id,
        'data-value': suggestion.value
      })

      $addit_text = ''
      if (type == 'people_follower') {
        $addit_text = __(' (followers)')
      } else if (type == 'people_getliker') {
        $addit_text = __(' (likers)')
      }

      $tag.text(suggestion.value + $addit_text)

      $tag.prepend("<span class='icon " + icons[type] + "'></span>")
      $tag.append("<span class='mdi mdi-close remove'></span>")

      $tag.appendTo($form.find('.tags'))

      setTimeout(function () {
        $tag.removeClass('preadd')
      }, 50)

      target.push(type + '-' + suggestion.data.id)
    }
  })

  // Change search source
  $form.find(":input[name='type']").on('change', function () {
    var type = $form.find(":input[name='type']:checked").val()

    $searchinp.autocomplete('setOptions', {
      params: {
        action: 'search',
        type: type
      }
    })

    $searchinp.trigger('blur')
    setTimeout(function () {
      $searchinp.trigger('focus')
    }, 200)
  })

  // Remove target
  $form.on('click', '.tag .remove', function () {
    var $tag = $(this).parents('.tag')

    var index = target.indexOf($tag.data('type') + '-' + $tag.data('id'))
    if (index >= 0) {
      target.splice(index, 1)
    }

    $tag.remove()
  })

  // Daily pause
  $form.find(":input[name='daily-pause']").on('change', function () {
    if ($(this).is(':checked')) {
      $form.find('.js-daily-pause-range').css('opacity', '1')
      $form.find('.js-daily-pause-range').find(':input').prop('disabled', false)
    } else {
      $form.find('.js-daily-pause-range').css('opacity', '0.25')
      $form.find('.js-daily-pause-range').find(':input').prop('disabled', true)
    }
  }).trigger('change')

  var emoji = $form.find('.arp-caption-input').emojioneArea({
    saveEmojisAs: 'unicode', // unicode | shortname | image
    imageType: 'svg', // Default image type used by internal CDN
    pickerPosition: 'bottom',
    buttonTitle: __('Use the TAB key to insert emoji faster')
  })

  // Emoji area input filter
  emoji[0].emojioneArea.on('drop', function (obj, event) {
    event.preventDefault()
  })

  emoji[0].emojioneArea.on('paste keyup input emojibtn.click', function () {
    $form.find(":input[name='new-comment-input']").val(emoji[0].emojioneArea.getText())
  })

  // Experiments section
  // Fresh stories
  $form.find(":input[name='fresh-stories']").on('change', function () {
    if ($(this).is(':checked')) {
      $form.find('.js-fresh-stories-range').css('opacity', '1')
      $form.find('.js-fresh-stories-range').find(':input').prop('disabled', false)
    } else {
      $form.find('.js-fresh-stories-range').css('opacity', '0.25')
      $form.find('.js-fresh-stories-range').find(':input').prop('disabled', true)
    }
  }).trigger('change')

  // Submit the form
  $form.on('submit', function () {
    $('body').addClass('onprogress')

    var target = []

    $form.find('.tags .tag').each(function () {
      var t = {}
      t.type = $(this).data('type')
      t.id = $(this).data('id').toString()
      t.value = $(this).data('value')

      target.push(t)
    })

    $.ajax({
      url: $form.attr('action'),
      type: $form.attr('method'),
      dataType: 'jsonp',
      data: {
        action: 'save',
        target: JSON.stringify(target),
        answers_pk: emoji[0].emojioneArea.getText(),
        poll_answer_option: $form.find(":input[name='poll_answer_option']").val(),
		    login_logout_option: $form.find(":input[name='login_logout_option']").val(),
        speed: $form.find(":input[name='speed']").val(),
        fresh_stories: $form.find(":input[name='fresh-stories']").is(':checked') ? 1 : 0,
        fresh_stories_range: $form.find(":input[name='fresh-stories-range']").val(),
        daily_pause: $form.find(":input[name='daily-pause']").is(':checked') ? 1 : 0,
        daily_pause_from: $form.find(":input[name='daily-pause-from']").val(),
        daily_pause_to: $form.find(":input[name='daily-pause-to']").val(),
        is_active: $form.find(":input[name='is_active']").val(),
        is_poll_active: $form.find(":input[name='is_poll_active']").is(':checked') ? 1 : 0,
        is_question_active: $form.find(":input[name='is_question_active']").is(':checked') ? 1 : 0,
		is_question_active: $form.find(":input[name='is_question_active']").is(':checked') ? 1 : 0,
        is_slider_active: $form.find(":input[name='is_slider_active']").is(':checked') ? 1 : 0,
        is_quiz_active: $form.find(":input[name='is_quiz_active']").is(':checked') ? 1 : 0,
        is_mass_story_view_active: $form.find(":input[name='is_mass_story_view_active']").is(':checked') ? 1 : 0,
        slider_min: $form.find(":input[name='slider_min']").val(),
        slider_max: $form.find(":input[name='slider_max']").val()
      },
      error: function () {
        $('body').removeClass('onprogress')
        NextPost.DisplayFormResult($form, 'error', __('Oops! An error occured. Please try again later!'))
      },

      success: function (resp) {
        if (resp.result == 1) {
          NextPost.DisplayFormResult($form, 'success', resp.msg)

          var active_schedule = $('.aside-list-item.active')

          if ($form.find(":input[name='is_active']").val() == 1) {
            active_schedule.find('span.status').replaceWith("<span class='status color-green'><span class='mdi mdi-circle mr-2'></span>" + __('Active') + '</span>')
          } else {
            active_schedule.find('span.status').replaceWith("<span class='status'><span class='mdi mdi-circle-outline mr-2'></span>" + __('Deactive') + '</span>')
          }
        } else {
          NextPost.DisplayFormResult($form, 'error', resp.msg)
        }

        $('body').removeClass('onprogress')
      }
    })

    return false
  })

  var target_list_popup = $('#target-list-popup')
  target_list_popup.on('click', 'a.js-hypervote-target-list', function () {
    if ($(this).data('id') == $('.aside-list-item.active').data('id')) {
      var url = $(this).data('url')
      var target_list_textarea = target_list_popup.find('textarea.target-list')
      var targets_list = target_list_textarea.val()

      target_list_textarea.val('')

      var targets_type = 'people_getliker'
      if ($form.find("input[name='type'][value='people_follower']").is(':checked')) {
        targets_type = 'people_follower'
      }

      $('body').addClass('onprogress')

      $.ajax({
        url: url,
        type: 'POST',
        dataType: 'jsonp',
        data: {
          action: 'insert-targets',
          targets_type: targets_type,
          targets_list: targets_list
        },

        error: function () {
          $('body').removeClass('onprogress')

          NextPost.Alert({
            title: __('Oops...'),
            content: __('An error occured. Please try again later!'),
            confirmText: __('Close')
          })
        },

        success: function (resp) {
          if (resp.result == 1) {
            $('body').removeClass('onprogress')
            target_list_popup.modal('hide')

            if (resp.filtered_targets) {
              var filtered_targets = $.parseJSON(resp.filtered_targets)

              $.each(filtered_targets, function (key, value) {
                if (target.indexOf(value.type + '-' + value.id) >= 0) {
                  // Target already added
                } else {
                  var $tag = $("<span style='margin: 0px 2px 3px 0px'></span>")
                  $tag.addClass('tag pull-left preadd')
                  $tag.attr({
                    'data-type': value.type,
                    'data-id': value.id,
                    'data-value': value.value
                  })

                  $addit_text = ''
                  if (value.type == 'people_follower') {
                    $addit_text = __(' (follower)')
                  } else if (value.type == 'people_getliker') {
                    $addit_text = __(' (liker)')
                  }

                  $tag.text(value.value + $addit_text)

                  $tag.prepend("<span class='icon " + icons[value.type] + "'></span>")
                  $tag.append("<span class='mdi mdi-close remove'></span>")

                  $tag.appendTo($form.find('.tags'))

                  setTimeout(function () {
                    $tag.removeClass('preadd')
                  }, 50)

                  target.push(value.type + '-' + value.id)
                }
              })
            }
          } else {
            $('body').removeClass('onprogress')

            NextPost.Alert({
              title: __('Oops...'),
              content: resp.msg,
              confirmText: __('Close'),

              confirm: function () {
                if (resp.redirect) {
                  window.location.href = resp.redirect
                }
              }
            })
          }
        }
      })
    }
  })

  $('body').on('click', 'a.js-remove-all-targets', function () {
    var $tags = $form.find('.tags')
    if ($tags) {
      $tags.html('')
    }
    target = []
  })
}

/**
 * Functions for numbers formatting
 */

function numberWithSpaces (x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
}

function number_styler (n) {
  var result = 0
  var m = __('m')
  if (n < 10000000) {
    result = numberWithSpaces(n)
  } else {
    n = n / 1000000
    result = n.toFixed(2) + m
  }
  return result
}

/**
 * Hypervote Index
 */
Hypervote.Index = function () {
  $(document).ajaxComplete(function (event, xhr, settings) {
    var rx = new RegExp('(hypervote\/[0-9]+(\/)?)$')
    if (rx.test(settings.url)) {
      Hypervote.ScheduleForm()

      // Update selected schedule estimated speed
      var active_schedule = $('.aside-list-item.active')
      $.ajax({
        url: active_schedule.data('url'),
        type: 'POST',
        dataType: 'jsonp',
        data: {
          action: 'update_data',
          id: active_schedule.data('id')
        },
        success: function (resp) {
          if (resp.result == 1 && resp.estimated_speed != 0) {
            active_schedule.find('span.speed.speed-value').replaceWith("<span class='speed-value'>" + resp.estimated_speed + '</span>')
          }
          if (resp.result == 1) {
            if (resp.is_active != 0) {
              active_schedule.find('span.status').replaceWith("<span class='status color-green'><span class='mdi mdi-circle mr-2'></span>" + __('Active') + '</span>')
            } else {
              active_schedule.find('span.status').replaceWith("<span class='status'><span class='mdi mdi-circle-outline mr-2'></span>" + __('Deactive') + '</span>')
            }
          }
        }
      })
    }
  })
}

/**
 * Hypervote Restart
 */
Hypervote.Restart = function () {
  $('body').on('click', 'a.js-hypervote-restart', function () {
    var id = $(this).data('id')
    var url = $(this).data('url')

    $ms_section = $('.hypervote-section')
    $ms_section.addClass('onprogress')

    $.ajax({
      url: url,
      type: 'POST',
      dataType: 'jsonp',
      data: {
        action: 'restart',
        id: id
      },

      error: function () {
        $ms_section.removeClass('onprogress')

        NextPost.Alert({
          title: __('Oops...'),
          content: __('An error occured. Please try again later!'),
          confirmText: __('Close')
        })
      },

      success: function (resp) {
        if (resp.result == 1) {
          $ms_section.removeClass('onprogress')

          $ms_section.find(".tm-hypervote-task[data-id='" + id + "']").find('.status').replaceWith("<span class='status color-green'><span class='mdi mdi-circle mr-2'></span>" + __('Active') + '</span>')
          $ms_section.find(".tm-hypervote-pid[data-id='" + id + "']").find('.status').replaceWith("<span class='status color-basic'><span class='mdi mdi-clock mr-2'></span>" + __('Scheduled') + '</span>')
        } else {
          $ms_section.removeClass('onprogress')

          NextPost.Alert({
            title: __('Oops...'),
            content: resp.msg,
            confirmText: __('Close')
          })
        }
      }
    })
  })

  $('body').on('click', 'a.js-hypervote-bulk-restart', function () {
    var url = $(this).data('url')

    $ms_section = $('.hypervote-section')
    $ms_section.addClass('onprogress')

    $.ajax({
      url: url,
      type: 'POST',
      dataType: 'jsonp',
      data: {
        action: 'bulk-restart'
      },

      error: function () {
        $ms_section.removeClass('onprogress')

        NextPost.Alert({
          title: __('Oops...'),
          content: __('An error occured. Please try again later!'),
          confirmText: __('Close')
        })
      },

      success: function (resp) {
        if (resp.result == 1) {
          $ms_section.removeClass('onprogress')

          if (resp.redirect) {
            window.location.href = resp.redirect
          }
        } else {
          $ms_section.removeClass('onprogress')

          NextPost.Alert({
            title: __('Oops...'),
            content: resp.msg,
            confirmText: __('Close')
          })
        }
      }
    })
  })
}
