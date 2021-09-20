import io from 'lodash';

export default function( subject ){
    if( io.isObjectLike( subject )) return io.mapValues( subject, function( value ){
        if( value && !isNaN( value )) return value * 1;
    });
    if( subject && !isNaN( subject )) return subject * 1;
    return subject;
}