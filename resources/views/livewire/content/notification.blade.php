<div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                bookRequestNotif();
                setInterval(function() {
                    bookRequestNotif();
                }, 2000); 

                
            });

            const bookRequestNotif = function() {
                @this.call('getBookRequestNotification').then(response => {
                    $('.badge-notif').text(response);
                });
            }
        </script>
        
    @endpush
</div>
