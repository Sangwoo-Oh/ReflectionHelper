import React, { useState } from "react";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import "../../../css/react-date-picker.css";
import { format } from "date-fns";

export default function Datepicker({ start_date, end_date }) {
  const [dateRange, setDateRange] = useState([start_date, end_date]);
  const [startDate, endDate] = dateRange;
  return (
    <>
        <input type="hidden" name="start_date" value={startDate ? format(startDate, "yyyy-MM-dd") : ""} />
        <input type="hidden" name="end_date" value={endDate ? format(endDate, "yyyy-MM-dd") : ""} />
        <DatePicker
            showIcon
            dateFormat="yyyy-MM-dd"
            className="form-control"
            selectsRange={true}
            startDate={startDate}
            endDate={endDate}
            onChange={(update) => {
                setDateRange(update);
            }}
            isClearable={true}
        />
    </>
  );
}
