/*global define, MathJax*/
define(['jquery', 'sounds'], function ($, play) {
    "use strict";
    var MultipleChoice;

    MultipleChoice = function () {
        function checkDimensions($self) {
            var totalWidth = 0,
                changed = false;

            $('.multiple-choice-answer-content', $self).each(function () {
                totalWidth += $(this).width();
                if (totalWidth > $self.width() || $(this).height() > 35) {
                    changed = true;
                    $self.addClass('extended');
                    return false;
                }
            });

            return changed;
        }

        function handleResize($self) {
            if (!$self.hasClass('extended')) {
                MathJax.Hub.Queue(['Typeset', MathJax.Hub, $self.get(0)]);
                MathJax.Hub.Queue(function () {
                    if (checkDimensions($self)) {
                        MathJax.Hub.Queue(['Reprocess', MathJax.Hub, $self.get(0)]);
                    }
                });
            }
        }

        return $(this).each(function () {
            var $self = $(this),
                $group = $('.multiple-choice-group', $self);

            handleResize($self);
            $(window).bind('resizeDelay', function () {
                handleResize($self);
            });

            $('.multiple-choice-answer-feedback, .multiple-choice-group-failure, .multiple-choice-group-success', $group).collapse({
                toggle: false
            });

            $self.click(function () {
                $self.addClass('active');
            });

            $('.multiple-choice-answer-content', $self).click(function (e) {
                e.preventDefault();
                $(this).toggleClass('active');
            });

            $('#content-layout').click(function (event) {
                if ($self.hasClass('active') &&
                    !$(event.target).closest($self).length &&
                    !$(event.target).is($self)) {
                    $self.removeClass('active');
                    $('.active', $self).removeClass('active');
                    $('.multiple-choice-answer-feedback', $self).not('.positive').collapse('hide');
                }
            });

            $group.submit(function (e) {
                e.preventDefault();
                var mistakes = 0,
                    solutions = [],
                    $submit = $('.multiple-choice-submit', $group),
                    $feedbackFailure = $('.multiple-choice-answer-feedback.negative', $group),
                    $feedbackSuccess = $('.multiple-choice-answer-feedback.positive', $group);

                $('.multiple-choice-answer-content', this).each(function (k, v) {
                    var $option = $(v),
                        answer = Boolean($option.hasClass('active')) || Boolean($option.hasClass('btn-success')),
                        correct = Boolean($option.data().correct);

                    if (correct && answer) {
                        changeClass($option, 'button-default', 'btn-success');
                        solutions.push($option);
                    } else if (correct && !answer) {
                        // correct solution not marked as correct
                        mistakes++;
                    } else if (!correct && answer) {
                        // wrong solution marked wrongfully
                        changeClass($option, 'button-default', 'btn-warning', 2000);
                        $option.siblings('.multiple-choice-answer-feedback').collapse('show');
                        mistakes++;
                    } else {
                        $option.siblings('.multiple-choice-answer-feedback').collapse('hide');
                    }
                });

                if (mistakes === 0) {
                    $feedbackFailure.collapse('hide');
                    $feedbackSuccess.collapse('show');
                    changeClass($submit, 'btn-primary', 'btn-success');
                    play('correct');
                } else {
                    $('.multiple-choice-answer-content.active', $self).removeClass('active');
                    changeClass($submit, 'btn-primary', 'btn-warning', 2000);
                    $feedbackFailure.collapse('show');
                    $feedbackSuccess.collapse('hide');
                    play('wrong');
                }
                return false;
            });
        });

        function changeClass($element, oldClasses, newClasses, time) {
            $element.removeClass(oldClasses).addClass(newClasses);
            if (time) {
                setTimeout(function () {
                    $element.removeClass(newClasses).addClass(oldClasses);
                }, time);
            }
        }
    };

    $.fn.MultipleChoice = MultipleChoice;
});
