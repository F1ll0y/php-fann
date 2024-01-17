ARG PHP_SRC_TYPE
ARG PHP_VERSION_MAJOR
ARG PHP_VERSION_MINOR
ARG PHP_VERSION_PATCH
ARG PHP_VERSION_RC

FROM parallelphp/php-$PHP_SRC_TYPE-$PHP_VERSION_MAJOR.$PHP_VERSION_MINOR:$PHP_VERSION_MAJOR.$PHP_VERSION_MINOR.$PHP_VERSION_PATCH$PHP_VERSION_RC

ARG PHP_SRC_TYPE

RUN test $PHP_SRC_TYPE != "gcov" || apt-get -y install lcov

ADD . /opt/fann

WORKDIR /opt/fann

RUN php -v

RUN phpize --clean

RUN phpize >/dev/null

RUN mkdir -p /opt/build/fann

WORKDIR /opt/build/fann

ARG PHP_SRC_ASAN
ARG PHP_SRC_GCOV

RUN /opt/fann/configure --enable-fann --$PHP_SRC_ASAN-fann-address-sanitizer --$PHP_SRC_GCOV-fann-gcov >/dev/null

RUN make -j >/dev/null

RUN make install >/dev/null

RUN echo "extension=fann.so" > \
        /opt/etc/php.d/fann.ini

RUN php -m

WORKDIR /opt/fann