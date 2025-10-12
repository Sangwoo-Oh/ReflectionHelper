import React, { useState } from 'react';
import { DataGrid } from '@mui/x-data-grid';
import { jaJP } from '@mui/x-data-grid/locales';
import Checkbox from '@mui/material/Checkbox';

export default function Table({
  events,
  freeword,
  keyword,
  excluded_string,
  start_date,
  end_date,
  csrfToken,
}) {
  const rows = events.map((event) => ({
    id: event.id,
    date: event.start_time.split('T')[0],
    summary: event.summary,
    start_time_h: event.start_time_h,
    end_time_h: event.end_time_h,
    duration_h: event.duration_h,
  }));

  const [selectedMap, setSelectedMap] = useState(() => new Map(rows.map((r) => [r.id, true])));

  const handleToggle = (id) => {
    setSelectedMap((prev) => {
      const next = new Map(prev);
      next.set(id, !next.get(id));
      return next;
    });
  };

  const columns = [
    {
      field: 'select',
      headerName: '集計対象',
      width: 80,
      sortable: false,
      filterable: false,
      disableColumnMenu: true,
      renderCell: (params) => (
        <Checkbox
          checked={!!selectedMap.get(params.id)}
          onChange={() => handleToggle(params.id)}
          onClick={(e) => e.stopPropagation()}
        />
      ),
    },
    { field: 'date', headerName: '日付', flex: 1 },
    { field: 'summary', headerName: 'スケジュール名', flex: 2 },
    { field: 'start_time_h', headerName: '開始時間', flex: 1 },
    { field: 'end_time_h', headerName: '終了時間', flex: 1 },
    { field: 'duration_h', headerName: '合計時間', flex: 1 },
  ];

  return (
    <form action="/aggregate" method="POST">
      <input type="hidden" name="_token" value={csrfToken} />
      <input type="hidden" name="reaggregate" value={1} />
      <input type="hidden" name="freeword" value={freeword} />
      <input type="hidden" name="keyword" value={keyword} />
      <input type="hidden" name="excluded_string" value={excluded_string} />
      <input type="hidden" name="start_date" value={start_date} />
      <input type="hidden" name="end_date" value={end_date} />
      {Array.from(selectedMap.entries()).map(
        ([id, selected]) =>
          selected && <input key={id} type="hidden" name="event_ids[]" value={id} />
      )}
      <div style={{ width: '100%', display: 'flex', flexDirection: 'column' }}>
        <DataGrid
          rows={rows}
          columns={columns}
          initialState={{
            pagination: { paginationModel: { page: 0, pageSize: 10 } },
          }}
          pageSizeOptions={[10, 20, 50, 100]}
          defaultRowSelectionModel={rows.map((r) => r.id)}
          sx={{ border: 0 }}
          showToolbar
          disableRowSelectionOnClick
          localeText={jaJP.components.MuiDataGrid.defaultProps.localeText}
        />
      </div>
      <div className="text-end mb-3">
        <button type="submit" className="btn btn-primary">
          再集計
        </button>
      </div>
    </form>
  );
}
