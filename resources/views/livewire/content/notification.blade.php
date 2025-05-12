<div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                bookRequestNotif();
                dueRequestNotif();
                setInterval(function() {
                    checkDue();
                    bookRequestNotif();
                    populateNotification();
                }, 2000); 

                
            });

            const dueRequestNotif = function() {
                @this.call('getDueRequestNotification').then(response => {
                    $('.badge-notif.due').text(response);
                });
            }

            const bookRequestNotif = function() {
                @this.call('getBookRequestNotification').then(response => {
                    $('.badge-notif.new').text(response);
                });
            }

            const populateNotification = function() {
                @this.call('getNotifications').then(notifications => {

                    var max_notif_num = 5;
                    var notif_counter = 0;

                    const notification_count = notifications.length;
                    setNotifCount(notification_count);

                    let $notificationList = $(".notification-list");

                    $notificationList.empty();

                    $.each(notifications, function(index, notification) {

                        if (notif_counter >= max_notif_num) {
                            return false;
                        }

                        var type = notification.data.type === "Due" ? "due" : "Info";

                        const timeAgo = moment(notification.created_at).fromNow();

                        let readStatus = '';

                        if (notification.read_at === null) {
                            readStatus = `
                                <p class="noti-time col-lg-6 text-end read-notif" data-notifiableid="${notification.notifiable_id}" data-notifid="${notification.id}">
                                    <span class="notification-time">Read</span>
                                </p>
                            `;
                        } else {
                            readStatus = '';
                        }

                        let notificationItem = `
                            <li class="notification-message">
                                <a>
                                    <div class="media d-flex">
                                        <span class="avatar flex-shrink-0">
                                            <img alt="" src="img/${type}.png">
                                        </span>
                                        <div class="media-body flex-grow-1">
                                            <p class="noti-details">
                                                <span class="noti-title">${notification.data.message}</span>
                                            </p>
                                            <div class="row">
                                                <p class="noti-time col-lg-6">
                                                    <span class="notification-time">${timeAgo}</span>
                                                </p>
                                                ${readStatus} 
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        `;

                        $notificationList.append(notificationItem);
                        $notificationList.attr('data-notifid', notification.notifiable_id);

                        notif_counter += 1;
                    });
                });
            };

            $(document).on('click', '.clear-noti', function() {

                let $notificationList = $(".notification-list");
                var notifId = $notificationList.data('notifid');
                $notificationList.empty();

                @this.call('readAll', notifId);

            });


            const checkDue = function() {
                @this.call('checkDue');
            }

            const setNotifCount = function(count) {
                $('.notif-count').text(count);
            }

            $(document).on('click', '.notification', function() {
                event.stopPropagation();

                const notification = $(this);
                const notifications = notification.siblings('.notifications');

                notification.toggleClass('show');

                const isOpen = notification.hasClass('show');
                notification.attr('aria-expanded', isOpen ? 'true' : 'false');

                if (isOpen) {
                    notifications.addClass('show');
                    notifications.css({
                        'position': 'absolute',
                        'inset': '0px 0px auto auto',
                        'margin': '0px',
                        'transform': 'translate(0px, 38px)'
                    });
                } else {
                    notifications.removeClass('show');
                    notifications.css({
                        'position': '',
                        'inset': '',
                        'margin': '',
                        'transform': ''
                    });
                }

            });

            const checkIfOpen = function() {
                const notification = $('.notification');
                const notifications = $('.notifications');

                let open = false;

                if (isOpen) {
                    notification.addClass('show');
                    notification.attr('aria-expanded', 'true');

                    notifications.addClass('show');
                    notifications.css({
                        'position': 'absolute',
                        'inset': '0px 0px auto auto',
                        'margin': '0px',
                        'transform': 'translate(0px, 38px)'
                    });
                } else {
                    notification.removeClass('show');
                    notification.attr('aria-expanded', 'false');

                    notifications.removeClass('show');
                    notifications.css({
                        'position': '',
                        'inset': '',
                        'margin': '',
                        'transform': ''
                    });
                }


                console.log(isOpen);
            }


            $(document).on('click', '.read-notif', function() {

                var notifId = $(this).data('notifid');
                var notifiableId = $(this).data('notifiableid');

                @this.call('readNotification', notifiableId, notifId).then(() => {});

                $(this).closest('li.notification-message').remove();

                console.log('Notification ID:', notifId);
            });
        </script>
        
    @endpush
</div>
