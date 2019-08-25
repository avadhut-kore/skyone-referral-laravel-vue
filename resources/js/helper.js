import moment from 'moment';
export function fromDateFormat (value) {
  return  moment(new Date(value)).format('MM/DD/YYYY');
}
/*let testmixin = {
    methods: {
        foo() {
            console.log('foo')
        },
    }
}
export const mixin =testmixin;*/
let dtFormat  = {
    data() {
        return {
            endstate : {
                disabledDates: {
                    to: null,
                }
            },
            startstate : {
                disabledDates: {
                    from: null,
                }
            },
            to_date : null
        }
    },
    methods: {
        formatStartDt(date) {
            var startdate = moment(date).format('DD-MM-YYYY');
            var restrictyear = moment(date).format('YYYY');
            var restrictmonth = moment(date).format('MM') - 1;
            var restrictday = moment(date).format('DD');
            this.endstate.disabledDates.to = new Date(restrictyear,restrictmonth, restrictday);
            return startdate;
        },
        formatEndDt(date) {
            var enddate = moment(date).format('DD-MM-YYYY');
            var restrictyear = moment(date).format('YYYY');
            var restrictmonth = moment(date).format('MM') - 1;
            var restrictday = moment(date).format('DD');
            this.startstate.disabledDates.from = new Date(restrictyear,restrictmonth, restrictday);
            return enddate;
        }
    }
}
export const formatDates =dtFormat;