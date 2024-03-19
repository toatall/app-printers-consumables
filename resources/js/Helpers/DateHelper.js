import moment from "moment";
moment.locale('ru');

export const DateHelper = {
    relative: date => moment(date).fromNow(),
    formatLong: date => moment(date).format('LLLL'),
    format: (date, format) => moment(date).format(format),
}