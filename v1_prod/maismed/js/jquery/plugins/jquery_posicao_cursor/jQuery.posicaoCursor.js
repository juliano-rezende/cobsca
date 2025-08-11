  /**

     * Plugin jQuery.posicaoCursor.js

     */

    ( function( $ ){

        $.fn.posicaoCursor = function( posicao ) {

            return this.each(function() {

                var $this = $( this );

                if ( $this.get(0).setSelectionRange ) {

                    $this.get(0).setSelectionRange( posicao, posicao );

                } else if ( $this.get(0).createTextRange ) {

                    var intervalo = $this.get(0).createTextRange();

                    intervalo.collapse( true );

                    intervalo.moveEnd('character', posicao);

                    intervalo.moveStart('character', posicao);

                    intervalo.select();

                }

            });

        };

    })( jQuery );