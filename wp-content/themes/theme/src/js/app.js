$(document).ready(function () {

    $('.lazy').lazy();

    if ($(window).width() < 992) {

        activeMenuTaskbar();
    }

    function activeMenuTaskbar() {
        if ($('body').hasClass('home')) {
            $('.menu-content .menu-item-1').addClass('active');
        } else if ($('body').hasClass('archive') || $('body').hasClass('single-product')) {
            $('.menu-content .menu-item-2').addClass('active');
        } else if ($('body').hasClass('page-template-tpl-checkout') || $('body').hasClass('woocommerce-cart')) {
            $('.menu-content .menu-item-3').addClass('active');
        } else if ($('body').hasClass('page-template-tpl-contact')) {
            $('.menu-content .menu-item-4').addClass('active');
        } else {
            $('.menu-content .menu-item-5').addClass('active');
        }
    }


    // check hiển báo lỗi không nhập trường bắt buộc khi nhấn nút đặt hàng
    $('#billing_first_name, #billing_custom_phone, #address_detail').on('input', function () {
        $(this).removeClass('error');
    });

    $('#address_city, #address_district, #address_wards').change(function () {
        if ($(this).val() === '0') {
            $(this).addClass('error');
        } else {
            $(this).removeClass('error');
        }
    });


    $('.order-btn').click(function () {
        $('#billing_first_name, #billing_custom_phone, #address_detail').removeClass('error');
        $('#address_city, #address_district, #address_wards').removeClass('error');

        if ($('#billing_first_name').val() === '') {
            $('#billing_first_name').addClass('error');
        }
        if ($('#billing_custom_phone').val() === '') {
            $('#billing_custom_phone').addClass('error');
        }
        if ($('#address_detail').val() === '') {
            $('#address_detail').addClass('error');
        }
        if ($('#address_city').val() === '0') {
            $('#address_city').addClass('error');
        }
        if ($('#address_district').val() === '0') {
            $('#address_district').addClass('error');
        }
        if ($('#address_wards').val() === '0') {
            $('#address_wards').addClass('error');
        }
    });

    // ========================

    $("body").on("click", ".menu-item", function (e) {
        // Loại bỏ lớp 'active' từ tất cả các menu-item ngoại trừ menu-item được nhấp
        $(".menu-item").not(this).removeClass("active");

        const dataId = $(this).data('id');
        $(".menu-mobile-custom-section").not("[data-id=" + dataId + "]").addClass('hide');

        // Xác định xem menu-item được nhấp có 'active' không
        const isActive = $(this).hasClass('active');

        // Loại bỏ lớp 'active' từ menu-item được nhấp và gọi lại activeMenuTaskbar nếu menu-item không còn 'active'
        if (isActive) {
            if ($('body')) {
                $(this).toggleClass('active');
                $(".menu-mobile-custom-section[data-id=" + dataId + "]").toggleClass('hide');
                activeMenuTaskbar();
            } else {
                $(this).removeClass('active');
                $(".menu-mobile-custom-section[data-id=" + dataId + "]").addClass('hide');
                activeMenuTaskbar();
            }

        } else {
            // Thêm lớp 'active' cho menu-item được nhấp nếu nó không có
            $(this).addClass('active');
            $(".menu-mobile-custom-section[data-id=" + dataId + "]").removeClass('hide'); // Fixed select
        }
    });

    $("body").on("click", "#close-menu-mobile-category", function () {
        const dataId = $(this).closest('.menu-mobile-custom-section').data('id').trim();
        $(".menu-item[data-id=" + dataId + "]").removeClass('active');
        $(this).closest('.menu-mobile-custom-section').addClass('hide');

        activeMenuTaskbar();
    });

    $(".menu-custom-item").each(function () {
        $(this).on("click", function () {
            $(".menu-custom-item").not($(this)).removeClass('active');
            $(this).addClass('active');
            const dataId = $(this).data('id');
            $(".sub-menu-wrapper").not("[data-id=" + dataId + "]").removeClass('active');
            $(".sub-menu-wrapper[data-id=" + dataId + "]").addClass('active'); // Fixed selector string
        });
    });


    //Banner Slider 
    $('.banner-carousel').owlCarousel({
        loop: true,
        nav: false,
        dots: true,
        items: 1,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
    });

    /**
     *
     * menu fixed mobile
     */
    if ($(window).width() <= 992) {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 60) {
                $("#menu-mobile-top").addClass('fixed-top');
            } else {
                $("#menu-mobile-top").removeClass('fixed-top');
            }
        });
    } else {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 20) {
                $("#menu-main").addClass('fixed-top');
                $(".logo-img").addClass('d-none');
                $(".logo-img-fixed").removeClass('d-none');
                $("#banner-main").css("padding-top", "20");

            } else {
                $("#menu-main").removeClass('fixed-top');
                $(".logo-img").removeClass('d-none');
                $(".logo-img-fixed").addClass('d-none');
                $("#banner-main").css("padding-top", "0");
            }

            if (!$("#menu-main").hasClass('fixed-top')) {
                $(".container.menu-category").css('top', '156.9px');
            } else {
                $(".container.menu-category").css('top', '115px');
            }

            if (!$('body').hasClass('home') && !$('body').hasClass('woocommerce-cart')
                && !$('body').hasClass('woocommerce-account')
                && !$('body').hasClass('page-template-tpl-news-php')
                && !$('body').hasClass('single-post')) {
                $("#menu-main").addClass('other-menu');
                $(".logo-img").addClass('d-none');
                $(".logo-img-fixed").removeClass('d-none');
                if ($(this).scrollTop() > 20) {
                    $("#menu-main").addClass('fixed-top');
                } else {
                    $("#menu-main").removeClass('fixed-top');
                    $("#banner-main").css("padding-top", "0");
                }
            }
        })
    }

    if (!$("#menu-main").hasClass('fixed-top')) {
        $(".container.menu-category").css('top', '156.9px');
    } else {
        $(".container.menu-category").css('top', '115px');

    }

    if (!$('body').hasClass('home') && !$('body').hasClass('woocommerce-cart')
        && !$('body').hasClass('woocommerce-account')
        && !$('body').hasClass('page-template-tpl-news-php')
        && !$('body').hasClass('single-post')
    ) {
        $("#menu-main").addClass('other-menu');
        $(".logo-img").addClass('d-none');
        $(".logo-img-fixed").removeClass('d-none');
        $(".sub-menu-wrapper").css('top', '-1px');
    }


    //Mở menu
    $('.box-category-scroll').click(function () {
        $('.popup-menu').toggleClass('active');
        $('.popup-menu').addClass('active');
        $('.menu-custom-wrapper .menu-custom li .sub-menu-wrapper').addClass('active');
    });

    $(document).mouseup(function (e) {
        var menu = $('.box-category-scroll');
        if (!menu.is(e.target) && menu.has(e.target).length === 0) {
            $('.popup-menu').removeClass('active');
            $('.menu-custom-wrapper .menu-custom li .sub-menu-wrapper').removeClass('active');
        }
    });
    /**
     * menu mobile
     */
    if ($(window).width() < 992) {
        $('.menu-item-has-children').click(function () {
            var th = $(this);
            th.children('ul').slideToggle()
        })
        $('.btn-open').click(function () {
            $('#showRightPush').toggleClass('active');
            $('.menu-active').toggleClass('show-menu-mb');
        });
    }
    document.addEventListener("click", function (event) {
        if (event.target.closest("#menu-main")) return;
        $('#showRightPush').removeClass('active');
        $('.menu-active').removeClass('show-menu-mb');
    });
    /**
     * back to top
     */
    // $(window).scroll(function () {
    //     if ($(this).scrollTop() > 100) {
    //         $(".scroll-up").fadeIn();
    //     } else {
    //         $(".scroll-up").fadeOut();
    //     }
    // });
    // $('#backToTop').on('click', function () {
    //     $("body,html").animate({scrollTop: 0}, "slow");
    // });

    //Trang chủ - Slide tin tức
    $('.news-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1.5,
                nav: true
            },
            576: {
                items: 1.5,
                nav: true
            },
            768: {
                items: 2,
                nav: true
            },
            992: {
                items: 3,
                nav: true
            },
            1200: {
                items: 4,
                nav: false
            },
        }
    });

    $('.owl-control').on("click", function () {
        $('.news-carousel.default').addClass('hidden-slide');
        const cateId = $(this).attr("data-cate");

        $('.news-carousel').each(function () {
            var dataCate = $(this).data('cate');
            if (dataCate == cateId) {
                $(this).removeClass('hidden-slide');
            } else {
                $(this).addClass('hidden-slide');
            }
        });

        $('.owl-control').each(function () {
            $(this).removeClass('active');
        });

        $(this).addClass('active');
    });

    //Trang chủ - Slide sản phẩm theo danh mục
    $('.product-carousel').owlCarousel({
        loop: true,
        margin: 14,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2.2,
                nav: true,
                margin: 8,
            },
            576: {
                items: 2.2,
                nav: true
            },
            768: {
                items: 3,
                nav: true
            },
            992: {
                items: 4,
                nav: true
            },
            1200: {
                items: 5,
                nav: false
            },
        }
    });
    $('.product-owl-control').on("click", function () {
        const cateId = $(this).attr("data-cate");
        const cateName = $(this).attr("data-name");
        $(`.product-carousel.default.${cateName}`).addClass('hidden-slide');

        $(`.product-carousel.${cateName}`).each(function () {
            var dataCate = $(this).data('cate');
            if (dataCate == cateId) {
                $(this).removeClass('hidden-slide');
            } else {

                $(this).addClass('hidden-slide');
            }
        });
    });

    //Trang chủ - Slide sản phẩm theo chương trình khuyến mãi nổi bật

    $('.program-feature-carousel').owlCarousel({
        loop: true,
        margin: 14,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2.2,
                nav: true,
                margin: 8,
            },
            576: {
                items: 2.2,
                nav: true,
            },
            768: {
                items: 3,
                nav: true
            },
            1200: {
                items: 4,
                nav: false
            },
            1400: {
                items: 5,
                nav: false
            },
        }
    });

    //chi tiet san pham - Slide sản phẩm liên quan trang chi tiết
    $('.program-releted-carousel').owlCarousel({
        loop: false,
        margin: 14,
        nav: true,
        dots: false,
        autoplayHoverPause: true,
        responsive: {
            320: {
                items: 2,
                nav: true
            },
            576: {
                items: 3,
                nav: true
            },
            768: {
                items: 4,
                nav: true
            },
            992: {
                items: 5,
                nav: false
            },
        }
    });


    //Trang chủ - Slider banner dưới danh mục
    if ($(window).width() < 992) {
        $('.banner-section.cate-banner').addClass('owl-carousel owl-theme')
        $('.banner-section.cate-banner').owlCarousel({
            loop: false,
            nav: false,
            dots: true,
            items: 1,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
        });
    }


    $('.item-header').on('click', function () {
        // index of the parent of the clicked button
        const parentIndex = $(this).attr("data-parent-index");
        // index of the clicked button
        const currentIndex = $(this).attr("data-index");
        const text_color = $(this).closest('.program-header').find('#text_color_active_tab').val().trim();

        // Show active tab
        $(`.item-header[data-parent-index="${parentIndex}"]`).removeClass('active');
        $(this).closest('.program-header').find(`.item-header div`).css("color", '#333 !important');
        $(`.item-header[data-parent-index="${parentIndex}"][data-index="${currentIndex}"]`).addClass('active');
        $(this).closest('.program-header').find(`.item-header.active div`).css("color", text_color + '!important');

        // Display data of the cateId
        $(`.program-feature-carousel[data-parent-index="${parentIndex}"]`).addClass('hidden-slide');
        $(`.program-feature-carousel[data-parent-index="${parentIndex}"][data-index="${currentIndex}"]`).removeClass('hidden-slide');
    });

    $('.search-input').on('click', function () {
        $('.popup-keyword').toggleClass('d-none');
    });

    $('.search-input').on('click', function () {
        $('.overlay-search').toggleClass('active');
    });

    $('.overlay-search').on('click', function () {
        $('.overlay-search').toggleClass('active');
        $('.popup-keyword').toggleClass('d-none');
    });

    $('.popup-keyword-item').on('click', function () {
        $('.search-input').val($(this).text().trim());
        setTimeout(() => {
            $('#submit-search').click();
        }, 200);
        $('.search .icon-search').addClass('d-none');
        $('.search .icon-close').removeClass('d-none');
    });

    $('.search-input').on('keyup', function () {
        if ($(this).val()) {
            $('.search .icon-search').addClass('d-none');
            $('.search .icon-close').removeClass('d-none');
        } else {
            $('.search .icon-search').removeClass('d-none');
            $('.search .icon-close').addClass('d-none');
        }
    });

    $('.search .icon-close').on('click', function () {
        $('.search-input').val('');
        $('.search .icon-search').removeClass('d-none');
        $('.search .icon-close').addClass('d-none');
    });

    //Giá tiền
    $(function () {
        $("#slider-range").slider({
            range: true,
            orientation: "horizontal",
            min: 0,
            max: 100000000,
            values: [0, 100000000],
            step: 1000000,

            slide: function (event, ui) {
                if (ui.values[0] == ui.values[1]) {
                    return false;
                }

                $("#min_price").val(vietnameseMoneyFormatted(ui.values[0]));
                $("#max_price").val(vietnameseMoneyFormatted(ui.values[1]));

                $('#min_price').data('number', ui.values[0]);
                $('#max_price').data('number', ui.values[1]);
            },

            change: function (event, ui) {
                filterProduct();
            }
        });

        $('#min_price').data('number', $("#slider-range").slider("values", 0));
        $('#max_price').data('number', $("#slider-range").slider("values", 1));

        $("#min_price").val(vietnameseMoneyFormatted($("#slider-range").slider("values", 0)));
        $("#max_price").val(vietnameseMoneyFormatted($("#slider-range").slider("values", 1)));

    });


    //Format VNĐ
    function vietnameseMoneyFormatted(number) {
        let value = number.toString().replace(/[^\d.]/g, '');
        let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return formattedValue + 'đ';
    }

    //Ajax load sản phẩm
    $('.paginate-ajax').click(function () {
        var current = $(this).data('current');
        var max = $(this).data('max');
        var id_category = $(this).data('category');
        var list = $('.list-products');
        var id_catgory = $('#id_catgory').val();
        var $this = $(this),
            array_serise = [],
            array_demand = [],
            array_color = [],
            array_source = [],
            price_min = $('#min_price').data('number'),
            price_max = $('#max_price').data('number'),
            order = $('.item-order.active').data('type');
        var data = {
            action: 'paginationProduct',
            current: current,
            max: max,
            id_category: id_category,
            array_serise: array_serise,
            array_demand: array_demand,
            array_color: array_color,
            array_source: array_source,
            price_min: price_min,
            price_max: price_max,
            order: order,
            id_catgory: id_catgory,
        }
        $.ajax({
            url: url_home + "/wp-admin/admin-ajax.php",
            type: 'post',
            dataType: 'json',
            data: data,
            beforeSend: function () {
                $('.loading').removeClass('d-none');
            },
            success: function (res) {
                $('.loading').addClass('d-none');
                list.append(res.result);
                if (Number(current) + 1 === Number(max)) {
                    $this.hide();
                }
                else {
                    $('#remaining').text(res.remaining_posts);
                    $this.data('current', Number(current) + 1);
                }
            }
        })
    })

    //Hàm thêm tiêu chi lọc
    function renderFilterCriteria(name, id) {
        var list = $('.filter-using-list');
        var html = '<li data-id="' + id + '" class="filter-using-item item-using-' + id + '"><span class="name">' + name + '</span><span class="icon-delete"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="15" viewBox="0 0 14 15" fill="none"><path d="M7 0.5C3.13306 0.5 0 3.63306 0 7.5C0 11.3669 3.13306 14.5 7 14.5C10.8669 14.5 14 11.3669 14 7.5C14 3.63306 10.8669 0.5 7 0.5ZM10.4323 9.3375C10.5649 9.47016 10.5649 9.68468 10.4323 9.81734L9.31452 10.9323C9.18185 11.0649 8.96734 11.0649 8.83468 10.9323L7 9.08064L5.1625 10.9323C5.02984 11.0649 4.81532 11.0649 4.68266 10.9323L3.56774 9.81452C3.43508 9.68185 3.43508 9.46734 3.56774 9.33468L5.41935 7.5L3.56774 5.6625C3.43508 5.52984 3.43508 5.31532 3.56774 5.18266L4.68548 4.06492C4.81814 3.93226 5.03266 3.93226 5.16532 4.06492L7 5.91935L8.8375 4.06774C8.97016 3.93508 9.18468 3.93508 9.31734 4.06774L10.4351 5.18548C10.5677 5.31814 10.5677 5.53266 10.4351 5.66532L8.58064 7.5L10.4323 9.3375Z" fill="white"/></svg></span></li>';
        list.append(html);
    }

    //Xóa từng tiêu chí
    $('body').on('click', '.filter-using-item .icon-delete', function () {
        var parent = $(this).parent();
        removedId = parent.data('id');
        $(`.filter-list input[value="${removedId}"]`).prop('checked', false).trigger('change');
    });

    //xóa tất cả tiêu chí 
    $('body').on('click', '.btn-delete-filter-using', function () {
        $('.filter-using-list li').each(function () {
            removedId = $(this).data('id');
            $(`.filter-list input[value="${removedId}"]`).prop('checked', false);
            $(this).remove();
        });
        filterProduct();
        $(this).addClass('d-none');
    });

    //Ẩn / hiện nút xóa tất cả bộ lọc
    $('.filter-list input').change(function () {
        if ($('.filter-using-list li').length > 0) {
            $('.btn-delete-filter-using').removeClass('d-none');
        } else {
            $('.btn-delete-filter-using').addClass('d-none');
        }
    })

    //checkbox thương hiệu/serise
    $('.list-series input[name="checkbox-serise"]').change(function () {
        var name = $(this).data('name');
        var id = $(this).val();
        var array = [];
        $('.filter-using-item').each(function () {
            array.push($(this).data('id'));
        });

        if (array.includes(Number(id)) === false) {
            renderFilterCriteria(name, id);
        }
        else {
            $('.item-using-' + id).remove();
        }
        filterProduct();

    });

    //checkbox nhu cầu
    $('.list-demand input[name="checkbox-demand"]').change(function () {
        var name = $(this).data('name');
        var id = $(this).val();
        var array = [];
        $('.filter-using-item').each(function () {
            array.push($(this).data('id'));
        });

        if (array.includes(Number(id)) === false) {
            renderFilterCriteria(name, id);
        }
        else {
            $('.item-using-' + id).remove();
        }
        filterProduct();
    });

    //checkbox màu sắc
    $('.list-color input[name="checkbox-color"]').change(function () {
        var name = $(this).data('name');
        var id = $(this).val();
        var array = [];
        $('.filter-using-item').each(function () {
            array.push($(this).data('id'));
        });

        if (array.includes(Number(id)) === false) {
            renderFilterCriteria(name, id);
        }
        else {
            $('.item-using-' + id).remove();
        }
        filterProduct();
    });

    //checkbox nguồn hàng
    $('.list-source input[name="checkbox-source"]').change(function () {
        var name = $(this).data('name');
        var id = $(this).val();
        var array = [];
        $('.filter-using-item').each(function () {
            array.push($(this).data('id'));
        });

        if (array.includes(Number(id)) === false) {
            renderFilterCriteria(name, id);
        }
        else {
            $('.item-using-' + id).remove();
        }
        filterProduct();
    });

    //Sắp xếp sản phẩm pc
    $('.list-order .item-order').click(function () {
        if ($(this).hasClass('active')) {
            $('.item-order').removeClass('active');
        }
        else {
            $('.item-order').removeClass('active');
            $(this).addClass('active');
        }
        filterProduct();
    })

    //sắp xếp sản phẩm trên ipad và mobile
    $('.list-order').change(function () {
        var selectedOption = $(this).find(':selected');
        if (selectedOption.hasClass('active')) {
            $('.item-order').removeClass('active');
        } else {
            $('.item-order').removeClass('active');
            selectedOption.addClass('active');
        }
        filterProduct();
    });

    //Hàm lọc sản phẩm theo yêu cầu
    function filterProduct() {
        $('.result-null').removeClass('active');
        var id_catgory = $('#id-category').val(),
            array_serise = [],
            array_demand = [],
            array_color = [],
            array_source = [],
            // price_min = $('#min_price').val(),
            // price_max = $('#max_price').val(),
            price_min = $('#min_price').data('number'),
            price_max = $('#max_price').data('number'),
            order = $('.item-order.active').data('type'),
            list = $('.list-products');
        $('.list-series input[name="checkbox-serise"]:checked').each(function () {
            array_serise.push($(this).val());
        });
        $('.list-demand input[name="checkbox-demand"]:checked').each(function () {
            array_demand.push($(this).val());
        });
        $('.list-color input[name="checkbox-color"]:checked').each(function () {
            array_color.push($(this).val());
        });
        $('.list-source input[name="checkbox-source"]:checked').each(function () {
            array_source.push($(this).val());
        });
        var data = {
            action: 'filterProduct',
            array_serise: array_serise,
            array_demand: array_demand,
            array_color: array_color,
            array_source: array_source,
            price_min: price_min,
            price_max: price_max,
            order: order,
            id_catgory: id_catgory,
        }
        $.ajax({
            url: url_home + "/wp-admin/admin-ajax.php",
            type: 'post',
            dataType: 'json',
            data: data,
            beforeSend: function () {
                $('.loading').removeClass('d-none');
            },
            success: function (res) {
                $('.loading').addClass('d-none');
                list.html(res.result);
                if (res.max_page < 1) {
                    $('.result-null').addClass('active');
                }
                if (res.max_page <= 1) {
                    $('.paginate-ajax').hide();
                }
                else {
                    $('.paginate-ajax').show();
                    $('.paginate-ajax').data('max', res.max_page);
                }
            }
        })
    }

    //Show điều kiện lọc
    $('.title-show').click(function () {
        $(this).find('img').toggleClass('active');
        $(this).parent().find('.box-show').slideToggle('slow');
    })

    //Slide hình ảnh sản phẩm trang chi tiết sản phẩm
    //Slide sản phẩm
    var swiper = new Swiper(".mySwiper", {
        direction: "vertical",
        freeMode: true,
        slidesPerView: 5,
        centeredSlides: false,
        loop: false,
        watchSlidesProgress: true,
        scrollbar: {
            el: ".swiper-scrollbar",
            hide: true,
        },
        breakpoints: {
            1280: {
                direction: "vertical",
            },
            320: {
                direction: "horizontal",
            },
        }
    });
    var swiper2 = new Swiper(".mySwiper2", {
        autoplay: {
            delay: 2000,
        },
        thumbs: {
            swiper: swiper,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });

    var swiper_single = new Swiper(".mySwiper-single", {
        spaceBetween: 10,
        slidesPerView: 5,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var swiper2_single = new Swiper(".mySwiper2-single", {
        spaceBetween: 10,
        thumbs: {
            swiper: swiper_single,
        },
    });

    //Show more cấu hình chi tiết
    if ($('.box-configuration .item-infor').length > 4) {
        $('.box-configuration .item-infor:gt(3)').hide();
        $('.see-configuration').css('display', 'flex');
        $('.see-configuration').show();
    }

    $('.see-configuration').on('click', function () {
        $('.box-configuration .item-infor:gt(3)').toggle();
        $(this).find('img').toggleClass('active');
        $(this).find('.text').html() === 'Xem thêm' ? $(this).find('.text').html('Rút gọn') : $(this).find('.text').html('Xem thêm');
    });

    //Show more thương hiệu
    if ($('.list-series .item-series').length > 2) {
        $('.list-series .item-series:gt(1)').hide();
        $('.see-more-series').show();
    }

    $('.see-more-series').click(function () {
        $('.list-series .item-series:gt(1)').toggle();
        $(this).find('img').toggleClass('active');
        $(this).find('.text').html() === 'Xem thêm' ? $(this).find('.text').html('Rút gọn') : $(this).find('.text').html('Xem thêm');
    })

    //Show more nhu cầu
    if ($('.list-demand .item-demand').length > 2) {
        $('.list-demand .item-demand:gt(1)').hide();
        $('.see-more-demand').show();
    }

    $('.see-more-demand').click(function () {
        $('.list-demand .item-demand:gt(1)').toggle();
        $(this).find('img').toggleClass('active');
        $(this).find('.text').html() === 'Xem thêm' ? $(this).find('.text').html('Rút gọn') : $(this).find('.text').html('Xem thêm');
    })

    //Show more màu sắc
    if ($('.list-color .item-color').length > 4) {
        $('.list-color .item-color:gt(3)').hide();
        $('.see-more-color').show();
    }

    $('.see-more-color').click(function () {
        $('.list-color .item-color:gt(3)').toggle();
        $(this).find('img').toggleClass('active');
        $(this).find('.text').html() === 'Xem thêm' ? $(this).find('.text').html('Rút gọn') : $(this).find('.text').html('Xem thêm');
    })



    //Show more ưu đãi
    if ($('.list-endow .item-endow').length > 3) {
        $('.list-endow .item-endow:gt(2)').hide();
        $('.see-endow').css('display', 'flex');
        $('.see-endow').show();

    }

    $('.see-endow').on('click', function () {
        $('.list-endow .item-endow:gt(2)').toggle();
        $(this).find('img').toggleClass('active');
        var type = $(this).attr('data-check');
        var count = $('.list-endow .item-endow').length;
        var count = count - 3;
        if (type === '0') {
            $(this).attr('data-check', '1');
            $(this).find('span').html('Rút gọn')
        }
        else {
            $(this).attr('data-check', '0');
            $(this).find('span').html('Xem thêm ' + count + ' ưu đãi khác')
        }
    });

    //Mở popup địa chỉ
    $('.btn-change-address.not-logged').click(function () {
        var popup = $('.popup-address.not-logged');
        popup.addClass('active');
    });

    $("body").on("click", ".btn-change-address.logged", function () {
        var popup = $('.popup-address.logged');
        popup.addClass('active');
    });

    $('.close-popup').click(function () {
        var popup = $(this).parent().parent();
        popup.removeClass('active');
    });

    $(document).mouseup(function (e) {
        var popup = $('.popup-address .popup-content');
        if (!popup.is(e.target) && popup.has(e.target).length === 0) {
            $('.popup-address').removeClass('active');
        }
    });

    //Show more mô tả chi tiết
    $('.see-more-content').click(function () {
        var parent = $(this).parent();
        var content = parent.find('.content');

        if ($(this).find('.text').text() === 'Xem thêm') {
            $(this).find('.text').text('Rút gọn');
            content.css('height', 'auto');
        }
        else {
            $(this).find('.text').text('Xem thêm');
            content.css('height', '910px');
        }
        $(this).find('img').toggleClass('active');
    })

    //Cộng trừ số lượng sản phẩm
    $('.btn__quality.plus').click(function () {
        var number = $('#quality').val();
        number = Number(number) + 1;
        $('#quality').attr('value', number);
        if (number > 1) {
            $('.btn__quality.minus').removeAttr('disabled');
            $('.btn__quality.minus').addClass('active');
        }

    });

    $('.btn__quality.minus').click(function () {
        var number = $('#quality').val();
        number = Number(number) - 1;
        $('#quality').attr('value', number);
        if (number <= 1) {
            $('.btn__quality.minus').prop('disabled', true);
            $('.btn__quality.minus').removeClass('active');
        }
    });

    //thay đổi giá theo biến thẻ sẩn phẩm
    $('.variation-radios input').change(function () {
        var id_att = $(this).attr('data-id');
        var slug = $(this).val();
        var id = $('input[name="product_id"]').val();
        var att = $(this).attr('name');
        var data = {
            action: 'get_price_variable',
            id_att: id_att,
            id: id,
            color: slug,
            att: att
        }
        $.ajax({
            url: url_home + "/wp-admin/admin-ajax.php",
            type: 'post',
            dataType: 'json',
            data: data,
            beforeSend: function () {
                $('.loading').removeClass('d-none');
            },
            success: function (res) {
                $('.loading').addClass('d-none');
                $('.cart-price').html(res.result);
            }
        })
    })

    //Load tỉnh thành quận huyện
    if ($('#single-input')[0]) {
        var renderData = (array, select) => {
            let select_text = 'Chọn tỉnh/thành phố';
            if (select === 'city-option') {
                select_text = 'Chọn tỉnh/thành phố';
                let row = ' <option disabled selected value="0">' + select_text + '</option>';
                array.forEach(element => {
                    row += `<option data-id="${element.level1_id}" value="${element.name}">${element.name}</option>`
                });
                document.querySelector("#" + select).innerHTML = row
            }
            if (select === 'district-option') {
                select_text = 'Chọn quận/huyện';
                let row = ' <option disabled selected value="0">' + select_text + '</option>';
                array.forEach(element => {
                    row += `<option data-id="${element.level2_id}" value="${element.name}">${element.name}</option>`
                });
                document.querySelector("#" + select).innerHTML = row
            }
            if (select === 'wards-option') {
                select_text = 'Chọn phường/xã';
                let row = ' <option disabled selected value="0">' + select_text + '</option>';
                array.forEach(element => {
                    row += `<option data-id="${element.level3_id}" value="${element.name}">${element.name}</option>`
                });
                document.querySelector("#" + select).innerHTML = row
            }
        }

        var callJSON = (url) => {
            var array = [];
            $.getJSON(url, function (res) {
                array = res.data;
                renderData(array, 'city-option');
            });

        }
        callJSON(url_template + '/json/dvhcvn.json');

        var callJsonDistrict = (url, id) => {
            var array = [];
            array_district = [];
            $.getJSON(url, function (res) {
                array = res.data;
                array.forEach(element => {
                    if (element.level1_id == id) {
                        array_district = element.level2s;
                        renderData(array_district, 'district-option');
                    }
                })
            });
        }

        var callJsonWard = (url, id) => {
            var array = [];
            var array_district = [];
            var array_wards = [];
            $.getJSON(url, function (res) {
                array = res.data;
                array.forEach(element => {
                    array_district = element.level2s;
                    array_district.forEach(element3 => {
                        if (element3.level2_id == id) {
                            array_wards = element3.level3s;
                            renderData(array_wards, 'wards-option');
                        }
                    });
                })
            });
        }

        $("#city-option").change(() => {
            var id = $("#city-option").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonDistrict(url, id);
        });

        $("#district-option").change(() => {
            var id = $("#district-option").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonWard(url, id);
        });
    }

    //Nhập địa chỉ
    $('.btn-apply').click(function () {
        var popup = $('.popup-address');
        var html = '';
        var id = '';
        var city = $("#city-option").find(':selected').text();
        var district = $("#district-option").find(':selected').text();
        var wards = $("#wards-option").find(':selected').text();
        html += city + ', ' + district + ', ' + wards;
        var id = city + ',' + district + ',' + wards;
        $('.address').find('.address-text').html(html);
        localStorage.setItem('title_address_not_logged', html);
        localStorage.setItem('address_not_logged', id);
        popup.removeClass('active');
    });

    //Load địa chỉ chọn bên chi tiết sản phẩm qua thanh toán (chưa đăng nhập)
    if ($('#id-checkout-page')[0] && localStorage.getItem('address_not_logged') && !$('.check-logged')[0]) {
        setTimeout(function () {
            var id = localStorage.getItem('address_not_logged');
            id = id.split(',');
            $('#address_city').val(id[0]).trigger('change');
            $('#address_district').val(id[1]).trigger('change');
            $('#address_wards').val(id[1]).trigger('change');
        }, 1000);
        setTimeout(function () {
            var id = localStorage.getItem('address_not_logged');
            id = id.split(',');
            $('#address_district').val(id[1]).trigger('change');
        }, 1500);
        setTimeout(function () {
            var id = localStorage.getItem('address_not_logged');
            id = id.split(',');
            $('#address_wards').val(id[2]).trigger('change');
        }, 2000);

    }



    //tải thêm 5 bài viết được xem nhiều nhất
    var total_post_view_most = $("#total_post_view_most").val();
    $("body").on('click', '#load_more', function () {

        $.ajax({
            url: url_home + "/wp-admin/admin-ajax.php",
            type: 'post',
            dataType: 'json',
            data: {
                action: 'loadmore_sidebar_news_outstanding',
                number: 5,
            },
            beforeSend: function () {
                $('.loading').removeClass('d-none');
            },
            success: function (res) {
                $('.loading').addClass('d-none');
                $('.sidebar-news-outstanding-list-wrapper').append(res.html);
                var total_post_view_most_current = $("#total_post_view_most_current").val();
                if (total_post_view_most_current == total_post_view_most) {
                    $('#load_more').hide();
                }
                page++;
            }
        })
    });

    /** 
    * Chỉnh vị trí cho hình ảnh trong editor 
    */
    const imgElements = $('img');

    imgElements.each(function () {
        const $this = $(this);

        if ($this.hasClass('aligncenter')) {
            $this.parent().css({
                'display': 'flex',
                'justify-content': 'center'
            });
        } else if ($this.hasClass('alignleft')) {
            $this.parent().css({
                'display': 'flex',
                'justify-content': 'flex-start'
            });
        } else if ($this.hasClass('alignright')) {
            $this.parent().css({
                'display': 'flex',
                'justify-content': 'flex-end'
            });
        }
    });

    /**
     * Validate Form Liên Hệ
     */

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    //validate phone
    function checkPhoneNumber(input) {
        var flag = false;
        var phones = input.val();
        if (phones) {
            var phone = phones.trim();
            phone = phone.replace("(+84)", "0");
            phone = phone.replace("+84", "0");
            phone = phone.replace("0084", "0");
            phone = phone.replace(/ /g, "");
            if (phone != "") {
                var firstNumber = phone.substring(0, 2);
                if (
                    (firstNumber == "06" ||
                        firstNumber == "05" ||
                        firstNumber == "04" ||
                        firstNumber == "09" ||
                        firstNumber == "08" ||
                        firstNumber == "03" ||
                        firstNumber == "07") &&
                    phone.length == 10
                ) {
                    if (phone.match(/^\d{10}/)) {
                        flag = true;
                    }
                }
            }
            return flag;
        }

    }

    /**
     * Hàm giới hạn số điện thoại
     * @param {*} input 
     */
    function limitPhone(input) {
        input.on("keypress", function (e) {
            if (e.target.value.length > 9) {
                e.preventDefault();
            }
        });
    } limitPhone($("#acf-field_66053d1aabaca"));


    /**
     * Hàm ràng buộc email
     */
    let validEmail = false;
    let validPhone = false;

    function validateEmail(input, show_error = true, check_now = false) {
        // Function to handle email validation
        function handleValidation() {
            var emailCorrect = input.val();
            if (!isEmail(emailCorrect)) {
                if (!show_error) {
                    input.addClass("no-notifi");
                    input.one("keypress", function () {
                        $(this).removeClass("no-notifi");
                    });
                } else {
                    input.parent().addClass("hasErrorEmail");
                    input.one("keypress", function () {
                        $(this).parent().removeClass("hasErrorEmail");
                    });
                }
                validEmail = false;
            } else {
                input.removeClass("no-notifi");
                input.parent().removeClass("hasErrorEmail");
                validEmail = true;
            }
            input.trigger("emailValidationComplete");
        }
        if (check_now) {
            handleValidation();
        }
        // Listen for change event for ongoing validation
        input.on("change", handleValidation);
    }

    /**
     * Hàm ràng buộc số điện thoại VN
     * @param {*} input 
     * @param {*} show_error 
     */
    function validatePhone(input, show_error = true, check_now = false) {
        function handleValidation() {
            if (!checkPhoneNumber(input)) {
                if (!show_error) {
                    input.addClass("no-notifi");
                    input.one("keypress", function () {
                        $(this).removeClass("no-notifi");
                    });
                } else {
                    input.parent().addClass("hasErrorPhone");
                    input.on("keypress", function () {
                        $(this).parent().removeClass("hasErrorPhone");
                    });
                }
                validPhone = false;
            } else {
                input.removeClass("no-notifi");
                input.parent().removeClass("hasErrorPhone");
                validPhone = true;
            }
            input.trigger("phoneValidationComplete");
        }

        if (check_now) {
            handleValidation();
        }

        input.on("change", handleValidation);
    }

    /**
     * Hàm cập nhật nút submit khi ràng buộc dữ liệu
     * @param {*} button 
     * @param {*} remove_variable_valid 
     */
    function updateSubmitButtonState(button = '', remove_variable_valid = '') {//thêm tham số là nút nhấn và xoá biến không cần thiết khi kiểm tra
        if (remove_variable_valid === 'validPhone') {
            if (!validEmail) {
                $(button).prop("disabled", true);
                $(button).css("opacity", "0.5");
                $(button).css("pointer-events", "none");
            } else {
                $(button).prop("disabled", false);
                $(button).css("opacity", "1");
                $(button).css("pointer-events", "auto");
            }
        } else if (remove_variable_valid === 'validEmail') {
            if (!validPhone) {
                $(button).prop("disabled", true);
                $(button).css("opacity", "0.5");
                $(button).css("pointer-events", "none");
            } else {
                $(button).prop("disabled", false);
                $(button).css("pointer-events", "auto");
                $(button).css("opacity", "1");
            }
        } else {
            if (!validPhone || !validEmail) {
                $(button).prop("disabled", true);
                $(button).css("opacity", "0.5");
                $(button).css("pointer-events", "none");
            } else {
                $(button).prop("disabled", false);
                $(button).css("pointer-events", "auto");
                $(button).css("opacity", "1");
            }
        }
    }

    /**
    * Form liên hệ
    */
    validateEmail($("#acf-field_66053d3babacb"), true, false);

    validatePhone($("#acf-field_66053d1aabaca"), true, false);

    $("#acf-field_66053d1aabaca").on("phoneValidationComplete", function () {
        updateSubmitButtonState($("#form_66053cd893484 .acf-button", 'validEmail'));
    });

    /**
    * Chọn ngày tháng năm trong trang tài khoản cá nhân
    */

    var selectedDay = ''; // Khai báo biến selectedDay ở global scope

    // Hàm để load ngày mặc định khi trang được tải
    function loadDays() {
        var days = 31; // Số ngày mặc định cho tháng
        for (var i = 1; i <= days; i++) {
            $('#day-select').append($('<option>', {
                value: i,
                text: i
            }));
        }
    }

    // Hàm để cập nhật ngày dựa trên tháng và năm được chọn
    function updateDays() {
        var month = $('#month-select').val();
        var year = $('#year-select').val();
        var daysInMonth = new Date(year, month, 0).getDate(); // Lấy số ngày trong tháng đã chọn

        // Kiểm tra nếu ngày đã chọn trước đó không nằm trong phạm vi của tháng mới và năm mới
        if (selectedDay && selectedDay > daysInMonth) {
            selectedDay = ''; // Reset ngày đã chọn nếu không hợp lệ với tháng mới và năm mới
        }

        // Xóa tất cả các tùy chọn ngày hiện có
        $('#day-select').empty();

        // Thêm tùy chọn cho số ngày mới
        for (var i = 1; i <= daysInMonth; i++) {
            $('#day-select').append($('<option>', {
                value: i,
                text: i
            }));
        }

        // Nếu đã có ngày được chọn trước đó, đặt giá trị cho dropdown ngày
        if (selectedDay) {
            $('#day-select').val(selectedDay);
        }
    }

    // Gọi hàm để load ngày mặc định khi trang được tải
    loadDays();

    // Gán sự kiện cho thay đổi tháng
    $('#month-select').change(updateDays);

    // Gán sự kiện cho thay đổi ngày để cập nhật biến selectedDay
    $('#day-select').change(function () {
        selectedDay = $(this).val();
    });

    /**
     * Gán thông tin cá nhân trường ngày tháng năm sinh
     */
    var birthday = $('#birthday_user_current').val();
    if (birthday) {
        // Phân tách ngày sinh thành ngày, tháng, năm
        var parts = birthday.split('/');
        var year = parseInt(parts[2]);
        var month = parseInt(parts[1]);
        var day = parseInt(parts[0]);
        $('#day-select').val(day);
        $('#month-select').val(month);
        $('#year-select').val(year);
    }

    //Mở nút lưu và huỷ khi nhấn cập nhật thông tin
    $("body").on('click', '#update-edit-account', function () {

        var parent = $(this).closest('.show-info');
        parent.addClass('active');
        parent.find('.btn-cancel-save-edit-account, input').removeClass('hidden');
        parent.find('.btn-update-edit-account, .text').addClass('hidden');
        var parentPassword = $(this).closest('.edit-account-password');
        parentPassword.addClass('active');
        if (parentPassword) {
            parentPassword.find('.woocommerce-form-row-group').removeClass('hidden');
        }
    });

    $("body").on('click', '#cancel-update-edit-account', function () {
        var parent = $(this).closest('.show-info');
        parent.removeClass('active');
        parent.find('.btn-cancel-save-edit-account, input').addClass('hidden');
        parent.find('.btn-update-edit-account, .text').removeClass('hidden');

        // Reset input values to their original text content
        parent.find('input').val(parent.find('.text').text().trim());

        var parentPassword = $(this).closest('.edit-account-password');
        parentPassword.removeClass('active');

        // Validate email and phone inputs after canceling
        validateEmailPhoneInMyaccount();

        // Check if parentPassword exists and hide the corresponding element
        if (parentPassword.length) {
            parentPassword.find('.woocommerce-form-row-group').addClass('hidden');
        }
    });

    /**
     Lưu thông tin cá nhân
    */
    $("body").on('click', '#save-personal-info-account', function () {
        const fullname = $("#account_display_name").val();
        const day = $("#day-select option:selected").val();
        const month = $("#month-select option:selected").val();
        const year = $("#year-select option:selected").val();
        const sex = $('input[name="gender"]:checked').val();
        const birthday = `${day}/${month}/${year}`;

        $.ajax({
            url: url_home + "/wp-admin/admin-ajax.php",
            type: 'post',
            dataType: 'json',
            data: {
                action: 'custom_save_info_my_account',
                fullname: fullname,
                birthday: birthday,
                sex: sex,
                type: 'update_personal_info'
            },
            beforeSend: function () {
                $('.loading').removeClass('d-none');
            },
            success: function (res) {
                $('.loading').addClass('d-none');
                $(".display-name-on-top-my-account-navigation span").text(res.data[0]);
                showToast(true, res.message);
            }
        })
    });

    /**
    * Lưu số điện thoại, email và mật khẩu
    */
    $(".save.update-edit-account").each(function () {
        $(this).on('click', function () {
            const button = $(this);
            const phone = button.closest('.show-info').find('#account_phone').val();
            const email = button.closest('.show-info').find('#account_email').val();
            const current_password = button.closest('.woocommerce-form-row-group').find('#password_current').val();
            const new_password = button.closest('.woocommerce-form-row-group').find('#password_1').val();
            const confirm_new_password = button.closest('.woocommerce-form-row-group').find('#password_2').val();

            if (phone) {
                runAjax(phone, 'phone');
            } else if (email) {
                runAjax(email, 'email');
            } else if (current_password && new_password && confirm_new_password) {
                const params = {
                    current_password: current_password,
                    new_password: new_password,
                    confirm_new_password: confirm_new_password
                };
                runAjax(params, 'password');
            }

            function runAjax(param, field) {
                $.ajax({
                    url: url_home + "/wp-admin/admin-ajax.php",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'custom_save_info_my_account',
                        param: param,
                        field: field,
                        type: 'update_phone_email_password'
                    },
                    beforeSend: function () {
                        $('.loading').removeClass('d-none');
                    },
                    success: function (res) {
                        $('.loading').addClass('d-none');
                        showToast(true, res.message);
                        button.closest('.show-info').find('#cancel-update-edit-account').click();
                        button.closest('.show-info').find(".text").text(res.data);
                        if (field === 'phone') {
                            button.closest('.show-info').find('#account_phone').val(res.data);
                        } else if (field === 'email') {
                            button.closest('.show-info').find('#account_email').val(res.data);
                        } else {
                            window.location.reload(true);
                        }
                    }
                })
            }
        })
    });

    /**
    * Validate số điện thoại và email trong myaccount
    */
    var validateEmailPhoneInMyaccount = () => {
        const email = $(".edit-account").find('#account_email');
        const phone = $(".edit-account").find('#account_phone');

        const button_email = email.closest('.show-info').find("#save-update-edit-account");
        const button_phone = phone.closest('.show-info').find("#save-update-edit-account");

        validateEmail(email, false, true);
        limitPhone(phone);
        validatePhone(phone, false, true);

        phone.on("phoneValidationComplete", function () {
            updateSubmitButtonState(button_phone, 'validEmail');
        });

        email.on("emailValidationComplete", function () {
            updateSubmitButtonState(button_email, 'validPhone');
        });

    }; validateEmailPhoneInMyaccount();

    /**
     * Gán thông tin tài khoản cá nhân
     */
    var currentSex = $('#sex_user_current').val();
    $('input[name="gender"]').filter('[value="' + currentSex + '"]').prop('checked', true);

    /**
     * Hiện thông báo thành công hoặc lỗi
     * @param {*} status 
     * @param {*} message 
     */
    function showToast(status, message) {

        var status_string = '';
        var toast_title = '';
        if (status) {
            status_string = 'success';
            toast_title = 'Thành công';
        } else {
            status_string = 'error';
            toast_title = 'Lỗi';
        }

        const toast = $('.toast');

        toast.find('.toast-content .toast-title').text(toast_title);
        toast.find('.toast-content .toast-message').text(message);
        toast.addClass(`${status_string} show`);
        setTimeout(function () {
            toast.removeClass('show');
        }, 5000);
    }


    /**
    * Bật/Tắt form thêm địa chỉ 
    */
    $("#button-add-address").on('click', function () {
        $(".form-add-address").addClass('show');
        if ($(".popup-address.logged").hasClass('active')) {
            $(".popup-address.logged").removeClass('active');
        }
    });

    $(".close-form-add-address").each(function () {
        $(this).on('click', function () {
            if ($(this).closest('.form-address').hasClass('show')) {
                $(this).closest('.form-address').removeClass('show');
            }
        })
    })

    /**
     * Tải tỉnh thành quận huyện (đã sửa lại) *
     * @param {*} array 
     * @param {*} parent_select 
     * @param {*} select 
     */

    if ($('.check-logged')[0]) {
        var renderProvinceDistrictsWards = (array, parent_select, select) => {
            let select_text = 'Chọn tỉnh/thành phố';
            if (select === 'city-option') {
                select_text = 'Chọn tỉnh/thành phố';
                let row = ' <option disabled selected value="0">' + select_text + '</option>';
                array.forEach(element => {
                    row += `<option data-id="${element.level1_id}" value="${element.name}">${element.name}</option>`
                });
                document.querySelector("." + parent_select + " #" + select).innerHTML = row;
            }
            if (select === 'district-option') {
                select_text = 'Chọn quận/huyện';
                let row = ' <option disabled selected value="0">' + select_text + '</option>';
                array.forEach(element => {
                    row += `<option data-id="${element.level2_id}" value="${element.name}">${element.name}</option>`
                });
                document.querySelector("." + parent_select + " #" + select).innerHTML = row;
            }
            if (select === 'wards-option') {
                select_text = 'Chọn phường/xã';
                let row = ' <option disabled selected value="0">' + select_text + '</option>';
                array.forEach(element => {
                    row += `<option data-id="${element.level3_id}" value="${element.name}">${element.name}</option>`
                });
                document.querySelector("." + parent_select + " #" + select).innerHTML = row;
            }
        }

        var callJsonCity = (url, parent_select) => {
            var array = [];
            $.getJSON(url, function (res) {
                array = res.data;
                renderProvinceDistrictsWards(array, parent_select, 'city-option');
            });

        }

        var callJsonDistrict = (url, parent_select, id) => {
            var array = [];
            array_district = [];
            $.getJSON(url, function (res) {
                array = res.data;
                array.forEach(element => {
                    if (element.level1_id == id) {
                        array_district = element.level2s;
                        renderProvinceDistrictsWards(array_district, parent_select, 'district-option');
                    }
                })
            });
        }

        var callJsonWard = (url, parent_select, id,) => {
            var array = [];
            var array_district = [];
            var array_wards = [];
            $.getJSON(url, function (res) {
                array = res.data;
                array.forEach(element => {
                    array_district = element.level2s;
                    array_district.forEach(element3 => {
                        if (element3.level2_id == id) {
                            array_wards = element3.level3s;
                            renderProvinceDistrictsWards(array_wards, parent_select, 'wards-option');
                        }
                    });
                })
            });
        }
    }

    /**
    * Hàm chạy Ajax CRUD địa chỉ
    */

    function add_update_remove_address(params, actionHandler) {
        let action = "";
        if (actionHandler === 'add') {
            action = "add_shipping_address";
        } else if (actionHandler === 'update') {
            action = "update_shipping_address";
        } else {
            action = "delete_shipping_address";
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: action,
                params: params
            },
            beforeSend: function () {
                $('.loading').removeClass('d-none');
            },
            success: function (response) {
                $('.loading').addClass('d-none');
                $(".form-address").each(function () {
                    if ($(this).closest('.form-address').hasClass('show')) {
                        $(".close-form-add-address").click();
                    }
                })
                location.reload(true);
            },
        });
    }

    $(".fields-address #new_phone").each(function () {

        $(this).on('keypress', function (e) {

            if (e.target.selectionStart === 0 && e.key === '0') {
                e.preventDefault();
            }

            if (e.target.value.length > 8) {
                e.preventDefault();
            }
        });

        $(this).on('change', function (e) {
            let phone = e.target.value;
            if (phone.match(/^\d{9}/)) {
                $(this).closest('.form-address').find('button').prop("disabled", false);
                $(this).closest('.form-address').find('button').css("opacity", "1");
                $(this).closest('.form-address').find('button').css("pointer-events", "auto");
            } else {
                $(this).addClass('no-notifi');
                $(this).on("keypress", function () {
                    $(this).removeClass('no-notifi');
                });
                $(this).closest('.form-address').find('button').prop("disabled", true);
                $(this).closest('.form-address').find('button').css("opacity", "0.5");
                $(this).closest('.form-address').find('button').css("pointer-events", "none");
            }
        })

    })



    /**
    * Thêm địa chỉ vào sổ địa chỉ
    */
    $("body").on("click", "#btn-apply-address", function () {

        const parent = $(this).closest('.form-add-address-wrapper');
        const user_name_new = parent.find("#new_name").val().trim();
        const user_phone_string = parent.find("#new_phone").val().trim();
        const user_phone_new = "0" + user_phone_string;
        const user_address_new = parent.find("#new_address").val().trim();
        const user_note = parent.find('#note').val().trim();

        const user_province_new = parent
            .find("#city-option option:selected")
            .val();
        const user_district_new = parent
            .find("#district-option option:selected")
            .val();
        const user_ward_new = parent.find("#wards-option option:selected").val();
        const user_province_id_new = parent
            .find("#city-option option:selected")
            .attr("data-id");
        const user_district_id_new = parent
            .find("#district-option option:selected")
            .attr("data-id");
        const user_ward_id_new = parent
            .find("#wards-option option:selected")
            .attr("data-id");

        //kiểm tra checked đặt làm mặc định
        let check_default = 0;
        if ($("#setting_default_address").is(":checked")) {
            check_default = 1;
        }

        let params = {
            user_name_new: user_name_new,
            user_phone_new: user_phone_new,
            user_address_new: user_address_new,
            user_note: user_note,
            user_province_new: user_province_new,
            user_district_new: user_district_new,
            user_ward_new: user_ward_new,
            user_province_id_new: user_province_id_new,
            user_district_id_new: user_district_id_new,
            user_ward_id_new: user_ward_id_new,
            check_default: check_default
        }

        if (user_name_new && user_phone_new && user_address_new && user_province_new && user_district_new && user_ward_new) {
            add_update_remove_address(params, 'add');
        }

    });

    /**
    * Xoá địa chỉ
    */
    $("body").on("click", "#btn-remove-address", function () {
        const idRowAddress = $(this).closest('.form-item-address').data('id');
        add_update_remove_address(idRowAddress);
    });

    /**
    * Lấy dữ liệu và gán vào Form cập nhật dữ liệu
    */
    $("body").on("click", "#btn-edit-address", function () {
        $(".form-update-address").addClass('show');
        if ($(".popup-address.logged").hasClass('active')) {
            $(".popup-address.logged").removeClass('active');
        }
        const parent = $(this).closest('.form-item-address');
        const idRow = parent.attr("data-id");

        const user_name = parent.find("#user_name").text().trim();
        const user_phone = parent.find("#user_phone").text().trim().substring(1);
        const user_note = parent.find('#user_note').val().trim();
        const user_address = parent.find("#user_address").data('address');
        const user_province = parent.find("#user_address").data('name-province');

        const user_district = parent.find("#user_address").data('name-district');
        const user_ward = parent.find("#user_address").data('name-ward');
        const user_province_id = parent.find("#user_address").data('id-province');
        const user_district_id = parent.find("#user_address").data('id-district');
        const user_ward_id = parent.find("#user_address").data('id-ward');
        const check_default = parent.find("#setting_default_address_item-" + idRow + "");

        if (check_default.is(":checked")) {

            $(".fields-address #setting_default_address_update").prop('checked', true);
        } else {
            $(".fields-address #setting_default_address_update").prop('checked', false);
        }


        callJsonCity(url_template + '/json/dvhcvn.json', "form-update-address");

        $(".form-update-address #city-option").change(() => {
            var id = $(".form-update-address #city-option").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonDistrict(url, "form-update-address", id);
        });

        $(".form-update-address #district-option").change(() => {
            var id = $(".form-update-address #district-option").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonWard(url, "form-update-address", id);
        });

        $(".form-update-address #idRow").val(idRow);
        $(".form-update-address .fields-address #new_name").val(user_name);
        $(".form-update-address .fields-address #new_phone").val(user_phone);
        $(".form-update-address .fields-address #new_address").val(user_address);
        $(".form-update-address .fields-address #note").val(user_note);

        setTimeout(() => {
            $(".form-update-address .fields-address #city-option").val(user_province);
            var url = url_template + '/json/dvhcvn.json';
            callJsonDistrict(url, "form-update-address", user_province_id);
            callJsonWard(url, "form-update-address", user_district_id);
            setTimeout(() => {
                $(".form-update-address .fields-address #district-option").val(user_district);
                $(".form-update-address .fields-address #wards-option").val(user_ward);
            }, 500);
        }, 500);
    });

    /**
    * Cập nhật địa chỉ
    */
    $(".form-update-address #btn-update-address").on('click', function () {
        const parent = $(this).closest('.form-update-address-wrapper');
        const user_name_new = parent.find("#new_name").val().trim();
        const user_phone_new_string = parent.find("#new_phone").val().trim();
        const user_phone_new = "0" + user_phone_new_string;
        const user_address_new = parent.find("#new_address").val().trim();
        const user_note = parent.find('#note').val().trim();
        const idRow = parent.find('#idRow').val().trim();

        const user_province_new = parent
            .find("#city-option option:selected")
            .val();
        const user_district_new = parent
            .find("#district-option option:selected")
            .val();
        const user_ward_new = parent.find("#wards-option option:selected").val();
        const user_province_id_new = parent
            .find("#city-option option:selected")
            .attr("data-id");
        const user_district_id_new = parent
            .find("#district-option option:selected")
            .attr("data-id");
        const user_ward_id_new = parent
            .find("#wards-option option:selected")
            .attr("data-id");

        //kiểm tra checked đặt làm mặc định
        let check_default = 0;
        if ($("#setting_default_address_update").is(":checked")) {
            check_default = 1;
        }

        let params = {
            idRow: idRow,
            user_name_new: user_name_new,
            user_phone_new: user_phone_new,
            user_address_new: user_address_new,
            user_note: user_note,
            user_province_new: user_province_new,
            user_district_new: user_district_new,
            user_ward_new: user_ward_new,
            user_province_id_new: user_province_id_new,
            user_district_id_new: user_district_id_new,
            user_ward_id_new: user_ward_id_new,
            check_default: check_default
        }

        if (user_name_new && user_phone_new && user_address_new && user_province_new && user_district_new && user_ward_new) {
            add_update_remove_address(params, 'update');
        }
    });

    /**
    * Đặt địa chỉ làm mặc định khi nhấn vào check
    */
    $('body').on("change", ".heading-form-item-address input[type='radio']", function () {
        const idRow = $(this).closest('.form-item-address').data('id');
        let check_default = 0;
        if ($(this).is(":checked")) {
            check_default = 1;
        }
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'check_default',
                check_default: check_default,
                idRow: idRow
            },
            beforeSend: function () {
                $('.loading').removeClass('d-none');
            },
            success: function (response) {
                $('.loading').addClass('d-none');
                location.reload(true);
            },
        });
    })

    /**
    * Bật select tỉnh thành quận huyện khi chọn địa chỉ khác 
    */
    $('.heading-form-item-address-single-product input[type="radio"]').not('#address_item_other').change(function () {
        $('.item-address_other').removeClass('show');
    });
    $("#address_item_other").change(function () {
        $('.item-address_other').toggleClass('show', $(this).is(':checked'));
    });



    /**
    * Load tỉnh thành quận huyện
    */
    if ($("#address_other")[0] && $('.check-logged')[0]) {
        callJsonCity(url_template + '/json/dvhcvn.json', "form-item-address-other");

        $(".form-item-address-other #city-option").change(() => {
            var id = $(".form-item-address-other #city-option").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonDistrict(url, "form-item-address-other", id);
        });

        $(".form-item-address-other #district-option").change(() => {
            var id = $(".form-item-address-other #district-option").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonWard(url, "form-item-address-other", id);
        });

    }

    if ($("#single-input")[0] && $('.check-logged')[0]) {
        callJsonCity(url_template + '/json/dvhcvn.json', "form-address");

        $(".form-address #city-option").change(() => {
            var id = $(".form-address #city-option").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonDistrict(url, "form-address", id);
        });

        $(".form-address #district-option").change(() => {
            var id = $(".form-address #district-option").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonWard(url, "form-address", id);
        });
    }

    /**
    * Nhấn nút áp dụng địa chỉ trong chi tiết sản phẩm
    */
    $("body").on("click", ".btn-apply-address-in-single-product", function () {
        if ($("#address_item_other").is(":checked")) {
            sessionStorage.clear('id_row_address');
            const parent = $("#address_item_other").closest('.form-item-address-other-wrapper');
            const user_province = parent
                .find("#city-option option:selected")
                .val();
            const user_district = parent
                .find("#district-option option:selected")
                .val();
            const user_ward = parent.find("#wards-option option:selected").val();
            const user_province_id = parent
                .find("#city-option option:selected")
                .attr("data-id");
            const user_district_id = parent
                .find("#district-option option:selected")
                .attr("data-id");
            const user_ward_id = parent
                .find("#wards-option option:selected")
                .attr("data-id");

            const address_other = {
                user_province: user_province,
                user_district: user_district,
                user_ward: user_ward,
                user_province_id: user_province_id,
                user_district_id: user_district_id,
                user_ward_id: user_ward_id,
            };

            sessionStorage.setItem('address_other', JSON.stringify(address_other));

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/wp-admin/admin-ajax.php",
                data: {
                    action: 'load_address_choosed',
                    fields: 'other',
                    params: address_other
                },
                beforeSend: function () {
                    $('.loading').removeClass('d-none');
                },
                success: function (response) {
                    $('.loading').addClass('d-none');
                    $('.popup-address.logged').removeClass('active');
                    $(".address.logged").html(response.html);
                },
            });

        } else {
            sessionStorage.clear('address_other');
            $(".form-item-address").each(function () {
                const idRow = $(this).data('id');

                if ($(".form-item-address #setting_default_address_item-" + idRow + "").is(":checked")) {
                    sessionStorage.setItem('id_row_address', idRow);
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "/wp-admin/admin-ajax.php",
                        data: {
                            action: 'load_address_choosed',
                            fields: 'similar',
                            idRow: idRow
                        },
                        beforeSend: function () {
                            $('.loading').removeClass('d-none');
                        },
                        success: function (response) {
                            $('.loading').addClass('d-none');
                            $('.popup-address.logged').removeClass('active');
                            $(".address.logged").html(response.html);
                        },
                    });
                }
            });
        }

    });

    //nếu tồn tại id của địa chỉ thì gán dữ liệu vào ô địa chỉ
    const id_row_address = sessionStorage.getItem("id_row_address");
    if (id_row_address) {
        $(".popup-address.logged #setting_default_address_item-" + id_row_address + "").prop("checked", true);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'load_address_choosed',
                fields: 'similar',
                idRow: id_row_address
            },
            beforeSend: function () {
                $('.load-img-small').addClass('active');
            },
            success: function (response) {
                $(".address.logged").html(response.html);
                $('.load-img-small').removeClass('active');
            },
        });
    }

    //Nếu tồn tại đỉa chỉ khác trong session thì tự động điền vào mục địa chỉ
    const address_other = JSON.parse(sessionStorage.getItem("address_other"));
    if (address_other) {
        $("#address_item_other").prop("checked", true);
        $('.item-address_other').addClass('show');
        const province = address_other['user_province'];
        const district = address_other['user_district'];
        const ward = address_other['user_ward'];

        setTimeout(() => {
            $(".item-address_other.show #city-option").val(province);
            var url = url_template + '/json/dvhcvn.json';
            callJsonDistrict(url, "item-address_other", address_other['user_province_id']);
            callJsonWard(url, "item-address_other", address_other['user_district_id']);
            setTimeout(() => {
                $(".item-address_other.show #district-option").val(district);
                $(".item-address_other.show #wards-option").val(ward);
            }, 500);
        }, 500);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'load_address_choosed',
                fields: 'other',
                params: address_other
            },
            beforeSend: function () {
                $('.load-img-small').addClass('active');
            },
            success: function (response) {
                $('.load-img-small').removeClass('active');
                $(".address.logged").html(response.html);
            },
        });


    }

    $(".item-delivery").each(function () {
        $(this).on("click", function () {
            $('.item-delivery').removeClass('active');
            $(this).addClass('active');

            const dataId = $(this).data('id');
            $(".describe p").removeClass('active');
            $(".describe p[data-id='" + dataId + "']").addClass('active');

        })
    });

    //Show chi tiết tình trạng đơn hàng
    $('#see-more-status').click(function () {
        var parent = $(this).parent();
        var content = parent.find('.content');
        content.addClass('active');

        if ($(window).width() <= 576) {
            if ($(this).find('.text').text() === 'Xem thêm') {
                $(this).find('.text').text('Rút gọn');
                content.css('height', 'auto');
            }
            else {
                $(this).find('.text').text('Xem thêm');
                content.css('height', '80px');
                content.removeClass('active');
            }
        } else {
            if ($(this).find('.text').text() === 'Xem thêm') {
                $(this).find('.text').text('Rút gọn');
                content.css('height', 'auto');
            }
            else {
                $(this).find('.text').text('Xem thêm');
                content.css('height', '60px');
                content.removeClass('active');
            }
        }

        $(this).find('img').toggleClass('active');
    });

    /**
    * Kiểm tra có bao nhiêu trạng thái trong chi tiết đơn hàng
    */



    $('.footer-table-wrapper .item').each(function () {
        var label = $(this).find('label').text().trim().toLowerCase();
        var slug = label.replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
        $(this).addClass(slug);
    });




    // Thêm thông báo trung tâm hỗ trợ nếu kích thước màn hình nhỏ hơn 992px
    if ($(window).width() > 992) {
        $('.page-template-tpl-support-mobile .sidebar-support').append('<h3 style="margin-top: 50px;text-align: center;">Trang chỉ hiển thị dưới kích thước ipad và mobile</h3>');
    }
    //add class overlay show fillter
    $('.box-fillter .btn-fillter').click(() => $('#category-products .sidebar-products').addClass('active'));
    $('.box-fillter .btn-fillter').click(() => $('#category-products .overlay').addClass('active'));
    $('#category-products .overlay').click(() => $('#category-products .sidebar-products').removeClass('active'));
    $('#category-products .overlay').click(() => $('#category-products .overlay').removeClass('active'));

    //Chọn tỉnh thành quận huyện
    // $('#address_city').select2();
    // $('#address_district').select2();
    // $('#address_wards').select2();
    $('#billing_tab_address').val(1);


    //tỉnh thành quận huyện
    if ($('#id-checkout-page')[0] && !$('.check-logged')[0]) {
        var renderData = (array, select) => {
            let select_text = 'Chọn tỉnh/thành phố';
            if (select === 'address_city') {
                select_text = 'Chọn tỉnh/thành phố';
                let row = ' <option disable value="0">' + select_text + '</option>';
                array.forEach(element => {
                    row += `<option data-id="${element.level1_id}" value="${element.name}">${element.name}</option>`
                });
                document.querySelector("#" + select).innerHTML = row
            }
            if (select === 'address_district') {
                select_text = 'Chọn quận/huyện';
                let row = ' <option disable value="0">' + select_text + '</option>';
                array.forEach(element => {
                    row += `<option data-id="${element.level2_id}" value="${element.name}">${element.name}</option>`
                });
                document.querySelector("#" + select).innerHTML = row
            }
            if (select === 'address_wards') {
                select_text = 'Chọn phường/xã';
                let row = ' <option disable value="0">' + select_text + '</option>';
                array.forEach(element => {
                    row += `<option data-id="${element.level3_id}" value="${element.name}">${element.name}</option>`
                });
                document.querySelector("#" + select).innerHTML = row
            }
        }

        var callJSON = (url) => {
            var array = [];
            $.getJSON(url, function (res) {
                array = res.data;
                renderData(array, 'address_city');
            });

        }
        callJSON(url_template + '/json/dvhcvn.json');

        var callJsonDistrict = (url, id) => {
            var array = [];
            array_district = [];
            $.getJSON(url, function (res) {
                array = res.data;
                array.forEach(element => {
                    if (element.level1_id == id) {
                        array_district = element.level2s;
                        renderData(array_district, 'address_district');
                    }
                })
            });
        }

        var callJsonWard = (url, id) => {
            var array = [];
            var array_district = [];
            var array_wards = [];
            $.getJSON(url, function (res) {
                array = res.data;
                array.forEach(element => {
                    array_district = element.level2s;
                    array_district.forEach(element3 => {
                        if (element3.level2_id == id) {
                            array_wards = element3.level3s;
                            renderData(array_wards, 'address_wards');
                        }
                    });
                })
            });
        }

        $("#address_city").change(() => {
            var id = $("#address_city").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonDistrict(url, id);
        });

        $("#address_district").change(() => {
            var id = $("#address_district").find(':selected').data('id');
            var url = url_template + '/json/dvhcvn.json';
            callJsonWard(url, id);
        });
    }


    //Chọn phương thức
    const chosen_shipping_methods = $("#chosen_shipping_methods").val();
    if (chosen_shipping_methods) {
        const escapedValue = chosen_shipping_methods.replace(':', '\\\\:');
        $(".method-delivery input[value='" + escapedValue + "']").prop('checked', true);
    } else {
        $('#method_shipping_flat_rate_0').prop('checked', true);
    }

    //ẩn phương thức nhận tại cửa hàng
    $(".method-delivery[data-id ='local_pickup']").addClass('d-none');

    $('input[name="method-delivery"]').change(function () {
        $(this).prop('checked', true);
        var value = $(this).val();

        $('input[name="shipping_method[0]"]').prop('checked', false);

        if (value == 'flat_rate:0') {
            $('#shipping_method_0_flat_rate1').prop('checked', true);
        }
        if (value == 'flat_rate:1') {
            $('#shipping_method_0_flat_rate2').prop('checked', true);
        }
        if (value == 'flat_rate:2') {
            $('#shipping_method_0_flat_rate3').prop('checked', true);
        }
        if (value == 'local_pickup:3') {
            $("#shipping_method_0_local_pickup4").prop('checked', true);
        }

        $('body').trigger('update_checkout');
    });


    // Khóa màn hình khi chọn phương thức thanh toán
    $('.method-list .method-delivery .method-name label').click(function () {
        $(document).ajaxSend(function (event, jqxhr, settings) {
            if (settings.url.indexOf('wc-ajax=update_order_review') !== -1) {
                $('.loading').removeClass('d-none');
            }
        });
        $(document).ajaxComplete(function (event, jqxhr, settings) {
            if (settings.url.indexOf('wc-ajax=update_order_review') !== -1) {
                $('.loading').addClass('d-none');
            }
        });
    });



    //Cộng trừ số lượng 
    $('.qty-delete .qty-plus').click(function (e) {
        e.preventDefault();
        var parent = $(this).parent();
        var number = parent.find('.qty').val();
        number = Number(number) + 1;
        parent.find('.qty').attr('value', number);
        if (number > 1) {
            parent.find('.qty-minus').prop('disabled', false)
            parent.find('.qty-minus svg path').css('stroke', '#212B36');
        }
        var add_cart = parent.parent().data('add_cart'),
            product_id = parent.parent().data('product_id'),
            variation_id = parent.parent().data('variation_id');
        var data = {
            action: 'woocommerce_ajax_update_qty_to_cart_session',
            product_id: product_id,
            quantity: number,
            variation_id: variation_id,
        };
        //Ajax cập nhật số lượng giỏ hàng
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: data,
            beforeSend: function (response) {
                $('.loading').removeClass('d-none');
            },
            success: function (res) {
                if (res.status == true) {
                    $('.item-cart-woo .qty').attr('value', number);
                    $("[name='update_cart']").prop('disabled', false);
                    setTimeout(function () {
                        $("[name='update_cart']").trigger("click");
                    }, 1000);
                }
            }
        })
    });

    $('.item-cart-session .qty-delete .qty-minus').click(function (e) {
        e.preventDefault();
        var parent = $(this).parent();
        var number = parent.find('.qty').val();
        number = Number(number) - 1;
        parent.find('.qty').attr('value', number);
        if (number <= 1) {
            parent.find('.qty-minus').prop('disabled', true)
            parent.find('.qty-minus svg path').css('stroke', '#E3E3E3');
        }
        $('.item-cart-woo .qty').attr('value', number);
        var add_cart = parent.parent().data('add_cart'),
            product_id = parent.parent().data('product_id'),
            variation_id = parent.parent().data('variation_id');
        var data = {
            action: 'woocommerce_ajax_update_qty_to_cart_session',
            product_id: product_id,
            quantity: number,
            variation_id: variation_id,
        };
        //Ajax cập nhật số lượng giỏ hàng
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: data,
            beforeSend: function (response) {
                $('.loading').removeClass('d-none');
            },
            success: function (res) {
                if (res.status == true) {
                    $('.item-cart-woo .qty').attr('value', number);
                    $("[name='update_cart']").prop('disabled', false);
                    setTimeout(function () {
                        $("[name='update_cart']").trigger("click");
                    }, 1000);
                }
            }
        })
    });

    //Chọn tất cả sản phẩm
    $('#product-all-checkbox').change(function () {
        if ($(this).prop('checked') === false) {
            var item = $('.list-product-cart .item-cart-session').length;
            if (item > 1) {
                $('.item-cart-session').find('input.selected_item_session').each(function () {
                    $(this).prop('checked', false);
                })
            }
            else {
                $(this).prop('checked', true);
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Bạn cần có ít nhất một sản phẩm được chọn',
                    showConfirmButton: true,
                    timer: 4000
                });
            }
        }
        else {
            $('.item-cart-session').find('input.selected_item_session').each(function () {
                $(this).prop('checked', true);
            })
        }
    });

    $('.item-cart-session input.selected_item_session').change(function () {
        var item = $('.list-product-cart .item-cart-session').length;
        flag = true;
        $('.item-cart-session input.selected_item_session').each(function () {
            if ($(this).prop('checked') == false) {
                flag = false;
                return false;
            }
        })
        if (flag == false) {
            $('#product-all-checkbox').prop('checked', false);
        }
        else {
            $('#product-all-checkbox').prop('checked', true);
        }
        if (item <= 1) {
            $(this).prop('checked', true);
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Bạn cần có ít nhất một sản phẩm được chọn',
                showConfirmButton: true,
                timer: 4000
            });
        }
    });

    //Chọn phương thức thanh toán
    $('input[name="payment_method_custom"]').change(function () {
        var value = $(this).val();
        $('#payment_method_' + value).prop('checked', true);
    });

    //Mở popup danh sách voucher
    $('.btn-show-coupon').click(function () {
        var popup = $('.popup-voucher');
        popup.addClass('active');
        $('#voucher_popup').val($('#coupon_code').val());
    });

    $('.popup-close').click(function () {
        var popup = $(this).parent().parent();
        popup.removeClass('active');
        applyMultipleCoupons(choosenCoupons);
    });

    $('.popup-clock').click(function () {
        var popup = $('.popup-voucher');
        popup.removeClass('active');
        applyMultipleCoupons(choosenCoupons);
    });

    //Copy voucher
    $(".btn-copy-code").click(function () {
        var copyText = $(this).parent().find('input');
        copyText.select();

        try {
            var successful = document.execCommand('copy');
            if (successful) {
                alert('Copy thành công')
            }
        } catch (err) {
            console.error('Unable to copy:', err);
        }
    });

    var choosenCoupons = localStorage.getItem('choosenCoupons');
    choosenCoupons = choosenCoupons ? JSON.parse(choosenCoupons) : [];
    function updateChoosenCoupons(code, action) {
        if (action === 'add') {
            var button = $(`.popup-voucher .item-voucher .content .right .btn-auto-voucher[data-code="${code}"]`);
            choosenCoupons.push(code);
            Array.from(new Set(choosenCoupons));
            localStorage.setItem('choosenCoupons', JSON.stringify(choosenCoupons));
            button.after(`<p class="btn-remove-voucher" data-code="${code}">Hủy</p>`);
            button.remove();
        } else {
            const index = choosenCoupons.indexOf(code);
            console.log(index);
            if (index > -1) {
                choosenCoupons.splice(index, 1);
                Array.from(new Set(choosenCoupons));
                localStorage.setItem('choosenCoupons', JSON.stringify(choosenCoupons));
                var button = $(`.popup-voucher .item-voucher .content .right .btn-remove-voucher[data-code="${code}"]`);
                button.after(`<p class="btn-auto-voucher" data-code="${code}">Áp dụng</p>`);
                button.remove();
            }
        }
    }

    function applyMultipleCoupons(couponList) {
        var data = {
            action: 'apply_coupons',
            coupons: couponList
        }
        $.ajax({
            url: url_home + "/wp-admin/admin-ajax.php",
            type: 'post',
            dataType: 'json',
            data: data,
            beforeSend: function () {
                $('.loading').removeClass('d-none');
            },
            success: function (res) {
                $('body').trigger('update_checkout');
                $('body').on('updated_checkout', function () {
                    $('.loading').addClass('d-none');
                });
                if (res.data.errors) {
                    res.data.errors.map(code => {
                        updateChoosenCoupons(code, 'remove');
                    });
                }
            },
        })
    }

    //Áp dụng voucher ở popup
    $('body').on('click', '.btn-auto-voucher', function () {
        var code = $(this).data('code');
        updateChoosenCoupons(code, 'add');
    });

    $('body').on('click', '.btn-remove-voucher', function () {
        var code = $(this).data('code');
        updateChoosenCoupons(code, 'remove');
    });

    //Xóa voucher
    $('body').on('click', '.woocommerce-remove-coupon', function () {
        var code = $(this).data('coupon').toUpperCase();
        updateChoosenCoupons(code, 'remove');
        applyMultipleCoupons(choosenCoupons);
    });

    $('.btn-coupon-popup').prop('disabled', true);
    $('.btn-coupon-popup').css('opacity', '0.6');
    $('#voucher_popup').on('input', function () {
        var inputVoucher = $(this).val().trim();
        var btnCoupon = $('.btn-coupon-popup');

        if (inputVoucher) {

            btnCoupon.prop('disabled', false);
            btnCoupon.css('opacity', '1');

        } else {
            btnCoupon.prop('disabled', true);
            btnCoupon.css('opacity', '0.6');
        }
    });

    $('.btn-coupon-popup').click(function () {
        var code = $('#voucher_popup').val();
        var popup = $('.popup-voucher');
        if (code === '') {
            alert('Nhập mã giảm giá');
        }
        else {
            popup.removeClass('active');
            $('input[name="coupon_code"]').val(code);
            $('button[name="apply_coupon"]').trigger('click');
        }
    });

    $('button[name="apply_coupon"]').click(function () {
        var code = $('input#coupon_code').val();
        if (code) {
            updateChoosenCoupons(code, 'add');
        }
    });

    //Clear localStorage khi dặt hàng thành công
    if ($('.woocommerce-order-received').length > 0 || $('.woocommerce-thankyou').length > 0) {
        console.log('Order successful, clearing specific localStorage items.');
        localStorage.removeItem('choosenCoupons');
    }



    // Mở yêu cầu xuất hóa đơn
    $('#request-export').change(function () {
        $('.form-export').toggleClass('active');

        var input = $('#billing_request_export');
        input.val(input.val() === '' ? 'Có' : '');
        checkedRequestExport($(this));
    });



    //Chọn tab giao hàng trang thanh toán
    $('.nav-address .item-address').click(function () {
        $('.nav-address .item-address').removeClass('active');
        $(this).addClass('active');
        var tab = $(this).data('id');
        $('.tab-address .item-tab').removeClass('active');
        $('#' + tab).addClass('active');
        var code = $(this).data('code');
        $('#billing_tab_address').val(code);
        if ($(this).data('id') == 'address-shop') {
            $("#shipping_method_0_local_pickup4").prop('checked', true);
            $(".method-shipping-custom").addClass('d-none');
        } else {
            $('#shipping_method_0_flat_rate1').prop('checked', true);
            $('#method_shipping_flat_rate_0').prop('checked', true);
            $(".method-shipping-custom").removeClass('d-none');
        }
        $('body').trigger('update_checkout');
    });

    if ($("#shipping_method_0_local_pickup4").is(":checked")) {
        $('.nav-address .item-address').removeClass('active');
        $('.tab-address .item-tab').removeClass('active');
        $(".item-address[data-id='address-shop']").addClass('active');
        $("#address-shop").addClass('active');
        $(".method-shipping-custom").addClass('d-none');
    } else {
        $(".item-address[data-id='address-shop']").removeClass('active');
        $("#address-shop").removeClass('active');
        $(".method-shipping-custom").removeClass('d-none');
    }

    //Đặt đơn hàng
    $('.order-btn').click(function () {
        var name_company = $('#name_company').val();
        var tax_code = $('#tax_code').val();
        var unit = $('#unit').val();
        var email_company = $('#email_company').val();
        $('#billing_company').attr('value', name_company);
        $('#billing_tax_code').attr('value', tax_code);
        $('#billing_unit').attr('value', unit);
        $('#billing_email_company').attr('value', email_company);
        var array_item = [];
        var array_un_item = [];
        var flag = 0;
        $('.selected_item_session').each(function () {
            if ($(this).prop('checked') == true) {
                flag += 1;
                array_item.push($(this).data('position'));
            }
            else {
                array_un_item.push($(this).data('position'));
            }
        });

        if (flag == 0) {
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Bạn cần có ít nhất một sản phẩm để được thanh toán.',
                showConfirmButton: true,
                timer: 4000
            });
        }
        else {
            var data = {
                action: 'woocommerce_ajax_order_to_cart_session',
                array_item: array_item,
                array_un_item: array_un_item
            }
            var name = $('#billing_first_name').val(),
                phone = $('#billing_custom_phone').val(),
                email = $('#billing_first_name').val(),
                check_people = $('#billing_check_people'),
                name_other = $('#billing_name_other').val(),
                phone_other = $('#billing_phone_other').val(),
                email_other = $('#billing_email_other').val(),
                address_city = $('#address_city').val(),
                address_district = $('#address_district').val(),
                address_wards = $('#address_wards').val(),
                // address_detail = $('#address_detail').val(),
                request_export = $('#request-export'),
                billing_company = $('#billing_company').val(),
                billing_tax_code = $('#billing_tax_code').val(),
                billing_unit = $('#billing_unit').val(),
                billing_email_company = $('#billing_email_company').val(),
                name_company = $('#name_company').val(),
                tax_code = $('#tax_code').val(),
                unit = $('#unit').val(),
                email_company = $('#email_company').val(),
                flag = true;
            if (name == '' || phone == '' || email == '' || address_city === '0' || address_district === '0' || address_wards === '0') {
                flag = false;
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Vui lòng nhập đầy đủ thông tin.',
                    showConfirmButton: true,
                    timer: 4000
                });

            }

            if (check_people.prop('checked') === true) {
                if (name_other == '' || phone_other == '' || email_other == '') {
                    flag = false;
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Vui lòng nhập đầy đủ thông tin.',
                        showConfirmButton: true,
                        timer: 4000
                    });
                }
            }
            if (request_export.prop('checked') === true) {
                if (billing_company == '' || billing_tax_code == '' || billing_unit == '' || billing_email_company == '') {
                    flag = false;
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Vui lòng nhập đầy đủ thông tin.',
                        showConfirmButton: true,
                        timer: 4000
                    });
                }

            }

            if (request_export.prop('checked') === true) {


                if (name_company == '' || tax_code == '' || unit == '' || email_company == '') {
                    console.log('active');
                    flag = false;
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Vui lòng nhập đầy đủ thông tin.',
                        showConfirmButton: true,
                        timer: 4000
                    });

                }
            }

            if (flag == true) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/wp-admin/admin-ajax.php",
                    data: data,
                    beforeSend: function (response) {
                        $('.loading').removeClass('d-none');
                    },
                    success: function (res) {
                        if (res.status == true) {
                            $('#place_order').trigger('click');
                        }
                    }
                })
            }
        }


    })


    //Toggle password
    $('img.toggle-pw').click(function () {
        var passwordInput = $(this).siblings('input');
        var type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
    })

    //Load thông tin  đặt hàng
    if (!sessionStorage.getItem('address_other') && !sessionStorage.getItem('id_row_address')) {
        var parent = $('input[name="address_customer"]:checked').parent();
        var data = parent.find('.data-address');
        var name = data.data('name');
        var gender = data.data('gender');
        var phone = data.data('phone');
        var email = data.data('email');
        if (gender == 'Nam') {
            gender = '0';
        }
        else {
            gender = '1';
        }
        $('input[name="billing_gender"]').attr('value', gender);
        $('input[name="billing_first_name"]').attr('value', name);
        $('input[name="billing_custom_phone"]').attr('value', phone);
        $('input[name="billing_email"]').attr('value', email);
    }
    else {
        if (sessionStorage.getItem('id_row_address')) {
            var id = sessionStorage.getItem('id_row_address');
            var parent = $('#address_' + id).parent();
            var data = parent.find('.data-address');
            var name = data.data('name');
            var gender = data.data('gender');
            var phone = data.data('phone');
            var email = data.data('email');
            if (gender == 'Nam') {
                gender = '0';
            }
            else {
                gender = '1';
            }
            $('input[name="billing_gender"]').attr('value', gender);
            $('input[name="billing_first_name"]').attr('value', name);
            $('input[name="billing_custom_phone"]').attr('value', phone);
            $('input[name="billing_email"]').attr('value', email);
        }
    }
    $('input[name="address_customer"]').change(function () {
        var parent = $(this).parent();
        var data = parent.find('.data-address');
        var name = data.data('name');
        var gender = data.data('gender');
        var phone = data.data('phone');
        var email = data.data('email');
        if (gender == 'Nam') {
            gender = '0';
        }
        else {
            gender = '1';
        }
        $('input[name="billing_gender"]').attr('value', gender);
        $('input[name="billing_first_name"]').attr('value', name);
        $('input[name="billing_custom_phone"]').attr('value', phone);
        $('input[name="billing_email"]').attr('value', email);
    });

    //Mở danh sách địa chỉ
    $('.open-list-address.active').click(function () {
        $('.list-address-of-customer').toggleClass('active');

    });

    //Mở popup chỉnh sửa và thêm mới địa chỉ
    $('.btn-address-checkout').click(function () {
        $(".form-add-address").addClass('show');
        if ($(".popup-address.logged").hasClass('active')) {
            $(".popup-address.logged").removeClass('active');
        }


    });

    // Custom error message
    $(document).ready(() => {
        let observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutationRecord) {
                if (mutationRecord.target.classList.contains("is-invalid")) {
                    $('.acf-notice.-error.acf-error-message p').get().forEach((item) => {
                        if (item.innerText.includes('required') || item.innerText.includes('bắt buộc')) {
                            var requiredErrorText = 'Vui lòng điền đầy đủ thông tin';
                            item.innerText = requiredErrorText;
                        }

                        if (item.innerText.includes('email')) {
                            var emailFormatErrorText = "Định dạng email không hợp lệ";
                            item.innerText = emailFormatErrorText;
                        }

                        if (item.innerText.includes('higher than')) {
                            item.innerText = 'Định dạng số điện thoại không hợp lệ';
                        }
                    })
                }
            })
        });

        const elements = document.querySelectorAll('form.af-form.acf-form');
        elements.forEach(element => observer.observe(element, {
            attributes: true,
            attributeFilter: ['style', 'class']
        }));
    });


    $('#billing_check_people_field .woocommerce-input-wrapper .checkbox input[type="checkbox"]').change(function () {
        if ($(this).is(':checked')) {
            $('#billing_gender_other_field, #billing_name_other_field, #billing_phone_other_field, #billing_email_other_field').addClass('active');
        } else {
            $('#billing_gender_other_field, #billing_name_other_field, #billing_phone_other_field, #billing_email_other_field').removeClass('active');
        }

    });

    //disabled form nhập mã giảm giá trong trang thanh toán
    $('.checkout_coupon .button').prop('disabled', true);
    $('#coupon_code').on('input', function () {
        var inputText = $(this).val().trim();

        if (inputText) {
            $('.checkout_coupon .button').prop('disabled', false);
        } else {
            $('.checkout_coupon .button').prop('disabled', true);
        }
    });

    //chuyển tab khi click vào chỉ đường trang thanh toán
    $('#address-shop .address-link').click(function (event) {

        event.preventDefault();
        var link_map = $(this).attr('href');
        window.open(link_map, '_blank');

    });

    //disabled button - khi số lượng là 1
    $('.quantity').each(function () {

        var input = $(this).find('.qty'),
            minusBtn = $(this).find('.qty-minus');

        input.on('change', function () {
            if (parseInt(input.val()) === 1) {
                minusBtn.prop('disabled', true).addClass('disabled');
            } else {
                minusBtn.prop('disabled', false).removeClass('disabled');
            }
        });

        if (parseInt(input.val()) === 1) {
            minusBtn.prop('disabled', true).addClass('disabled');
        }
    });


    $(".product-cate-item .header .sub-cate .item-1").addClass("active");


    $('.product-cate-item .header .sub-cate').each(function () {
        $(this).find('.item').click(function () {
            // Xóa lớp 'active' khỏi tất cả các span trong cùng một hàng
            $(this).siblings().removeClass('active');
            // Thêm lớp 'active' cho span được click
            $(this).addClass('active');
        });
    });


    /**
    * Đăng xuất
    */
    $(".woocommerce-MyAccount-navigation-link--customer-logout").click(function (e) {
        e.preventDefault();
        $.ajax({
            url: url_home + '/wp-admin/admin-ajax.php',
            dataType: 'json',
            type: 'POST',
            data: {
                action: 'custom_ajax_logout'
            },
            beforeSend: function () {
                $(".loading").removeClass('d-none');
            },
            success: function (response) {
                $(".loading").addClass('d-none');
                if (response.success) {
                    if ($("body").hasClass("woocommerce-account")) {
                        window.location.href = '/';
                    } else {
                        window.location.href = response.data.redirect_url;
                    }
                } else {
                    console.log('Logout failed');
                }
            }
        });
    });

    //Thêm sản phẩm vào giỏ hàng giả (danh mục sản phẩm và trang chủ)
    $('body').on('click', '.add_cart_ajax_session', function () {
        var product_id = $(this).data('product_id');
        var product_qty = 1;
        var variation_id = $(this).data('variation_id');
        var id_fee = '';
        var data = {
            action: 'woocommerce_ajax_add_to_cart_session',
            product_id: product_id,
            quantity: product_qty,
            variation_id: variation_id,
            id_fee: id_fee
        };
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: data,
            beforeSend: function (response) {
                $('.loading').removeClass('d-none');
            },
            complete: function (response) {
                $('.loading').addClass('d-none');
            },
            success: function (res) {
                if (res.status == true) {
                    $('.cart-header').attr('href', '/thanh-toan')
                    $('.popup-add-cart-success').addClass('active');
                    setTimeout(function () {
                        $('.popup-add-cart-success').removeClass('active');
                    }, 2000)
                    $('.number-cart').html(res.number_item_cart);
                    $('.cart-list').html(res.result);
                }
            }
        });
    })

    //Thêm sản phẩm trong trang chi tiết sản phẩm
    $('body').on('click', '.add_cart_ajax_single_session', function () {
        var product_id = $('input[name="product_id"]').val(),
            product_qty = $('input[name="quality"]').val(),
            variation_id = $('input[name="attribute_pa_mau-sac"]:checked').attr('data-id');
        id_fee = $('.item-delivery.active').data('id');
        var data = {
            action: 'woocommerce_ajax_add_to_cart_session_single',
            product_id: product_id,
            quantity: product_qty,
            variation_id: variation_id,
            id_fee: id_fee
        };
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: data,
            beforeSend: function (response) {
                $('.loading').removeClass('d-none');
            },
            complete: function (response) {
                $('.loading').addClass('d-none');
            },
            success: function (res) {
                if (res.status == true) {
                    $('.cart-header').attr('href', '/thanh-toan')
                    $('.popup-add-cart-success').addClass('active');
                    setTimeout(function () {
                        $('.popup-add-cart-success').removeClass('active');
                    }, 2000)
                    $('.number-cart').html(res.number_item_cart);
                    $('.cart-list').html(res.result);
                    //console.log(res);
                }
            }
        });
    })

    //Nút mua ngay trang chi tiết sản phẩm
    $('body').on('click', '.buy_ajax_single_session', function () {
        var product_id = $('input[name="product_id"]').val(),
            product_qty = $('input[name="quality"]').val(),
            variation_id = $('input[name="attribute_pa_mau-sac"]:checked').attr('data-id'),
            id_fee = $('.item-delivery.active').data('id');
        var payment_method = 'bacs';
        if ($(this).hasClass('installment-link')) {
            payment_method = $(this).data('id');
        }
        var data = {
            action: 'woocommerce_ajax_buy_to_cart_session_single',
            product_id: product_id,
            quantity: product_qty,
            variation_id: variation_id,
            id_fee: id_fee,
            payment_method: payment_method,
        };

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: data,
            beforeSend: function (response) {
                $('.loading').removeClass('d-none');
            },
            complete: function (response) {
                $('.loading').addClass('d-none');
            },
            success: function (res) {
                if (res.status == true) {
                    $('.cart-header').attr('href', '/thanh-toan')
                    $('.popup-add-cart-success').addClass('active');
                    setTimeout(function () {
                        $('.popup-add-cart-success').removeClass('active');
                    }, 2000)
                    $('.number-cart').html(res.number_item_cart);
                    $('.cart-list').html(res.result);
                    $(location).attr('href', url_home + '/thanh-toan');
                }

            }
        });
    });

    if ($("#shipping_method")[0]) {
        setTimeout(() => {
            const shipping_method = $("#shipping_method").val();
            if (shipping_method == 'local_pickup:4') {
                $("#shipping_method_0_local_pickup4").prop('checked', true);
                $(".method-shipping-custom").addClass('d-none');
                $(".item-address").removeClass('active');
                $(".item-address[data-id='address-shop']").addClass('active');
                setTimeout(() => {
                    $('body').trigger('update_checkout');
                }, 500);
            } else {
                if (shipping_method == 'flat_rate:1') {
                    $('#method_shipping_flat_rate_0').prop('checked', true);
                    $('#shipping_method_0_flat_rate1').prop('checked', true);
                }
                if (shipping_method == 'flat_rate:2') {
                    $('#method_shipping_flat_rate_1').prop('checked', true);
                    $('#shipping_method_0_flat_rate2').prop('checked', true);
                }
                if (shipping_method == 'flat_rate:3') {
                    $('#method_shipping_flat_rate_2').prop('checked', true);
                    $('#shipping_method_0_flat_rate3').prop('checked', true);
                }
                setTimeout(() => {
                    $('body').trigger('update_checkout');
                }, 500);
            }
        }, 500);
    }

    //Xóa sản phẩm
    $('.item-cart-session .remove-item .btn-remove-product').click(function () {
        var product_id = $(this).data('product_id');
        var variation_id = $(this).data('variation_id');
        var data = {
            action: 'woocommerce_ajax_remove_to_cart_session',
            product_id: product_id,
            variation_id: variation_id,
        }
        var item = $('.list-product-cart .item-cart-session').length;
        if (item > 1) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/wp-admin/admin-ajax.php",
                data: data,
                beforeSend: function (response) {
                    $('.loading').removeClass('d-none');
                },
                success: function (res) {
                    if (res.status == true) {
                        var url = $('#id-product-' + product_id + '-' + variation_id).find('.qty-delete .remove-item a').attr('href');
                        window.location.href = url;
                    }
                }
            })
        }
        else {
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Bạn cần có ít nhất một sản phẩm được chọn',
                showConfirmButton: true,
                timer: 4000
            });
        }
    });

    //Ham lưu lại sản phẩm trong giỏ hàng 
    if ($('#thankyou-ajax-update-cart')[0]) {
        var data = {
            action: 'thankyou_ajax_update_cart'
        }
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: data,
            success: function (res) {
            }
        })
    }

    $(".title-mobile #goBack").click(function () {
        var link = $(this);
        link.addClass('active');
        setTimeout(function () {
            link.removeClass('active');
        }, 300);
    });


    // custom woocommerce-breadcrumb trang chi tiết đơn hàng
    var link = $('<a>', {
        href: url_orders,
        text: 'Đơn hàng'
    });
    
    $('.woocommerce-view-order .woocommerce-breadcrumb').last().contents().last().filter(function() {
        return this.nodeType === Node.TEXT_NODE;
    }).before('&nbsp;/&nbsp;', link);
   

});