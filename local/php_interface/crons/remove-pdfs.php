<?php

// Удаление временных PDF с рендерами, просмотренными более суток назад.
// exec  `find ./upload/orders/prerenders/ -type f -atime +1 -exec rm -f {} \;`