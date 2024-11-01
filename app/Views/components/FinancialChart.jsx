import React, { useState, useEffect } from 'react';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Select } from '@/components/ui/select';

const FinancialChart = () => {
  const [filterType, setFilterType] = useState('harian');
  const [selectedMonth, setSelectedMonth] = useState('11');
  const [selectedYear, setSelectedYear] = useState('2024');
  const [chartData, setChartData] = useState([]);

  const months = [
    { value: '01', label: 'Januari' },
    { value: '02', label: 'Februari' },
    { value: '03', label: 'Maret' },
    { value: '04', label: 'April' },
    { value: '05', label: 'Mei' },
    { value: '06', label: 'Juni' },
    { value: '07', label: 'Juli' },
    { value: '08', label: 'Agustus' },
    { value: '09', label: 'September' },
    { value: '10', label: 'Oktober' },
    { value: '11', label: 'November' },
    { value: '12', label: 'Desember' }
  ];

  const years = Array.from({ length: 5 }, (_, i) => ({
    value: `${2020 + i}`,
    label: `${2020 + i}`
  }));

  const generateSampleData = () => {
    let data = [];
    
    if (filterType === 'harian') {
      // Generate daily data for selected month
      const daysInMonth = new Date(parseInt(selectedYear), parseInt(selectedMonth), 0).getDate();
      for (let i = 1; i <= daysInMonth; i++) {
        data.push({
          date: `${selectedYear}-${selectedMonth}-${i.toString().padStart(2, '0')}`,
          pemasukan: Math.random() * 5000000,
          pengeluaran: Math.random() * 3000000
        });
      }
    } else if (filterType === 'bulanan') {
      // Generate monthly data for selected year
      for (let i = 1; i <= 12; i++) {
        data.push({
          date: `${selectedYear}-${i.toString().padStart(2, '0')}`,
          pemasukan: Math.random() * 15000000,
          pengeluaran: Math.random() * 10000000
        });
      }
    } else {
      // Generate yearly data
      for (let i = 2020; i <= 2024; i++) {
        data.push({
          date: `${i}`,
          pemasukan: Math.random() * 180000000,
          pengeluaran: Math.random() * 120000000
        });
      }
    }
    
    return data;
  };

  useEffect(() => {
    setChartData(generateSampleData());
  }, [filterType, selectedMonth, selectedYear]);

  const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR'
    }).format(value);
  };

  const formatDate = (date) => {
    if (filterType === 'harian') {
      return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    } else if (filterType === 'bulanan') {
      return new Date(date + '-01').toLocaleDateString('id-ID', { month: 'long' });
    }
    return date;
  };

  return (
    <Card className="w-full">
      <CardHeader>
        <CardTitle>Grafik Keuangan</CardTitle>
        <div className="flex gap-4">
          <Select
            value={filterType}
            onValueChange={setFilterType}
            className="w-32"
          >
            <option value="harian">Harian</option>
            <option value="bulanan">Bulanan</option>
            <option value="tahunan">Tahunan</option>
          </Select>
          
          {filterType !== 'tahunan' && (
            <Select
              value={selectedYear}
              onValueChange={setSelectedYear}
              className="w-32"
            >
              {years.map(year => (
                <option key={year.value} value={year.value}>{year.label}</option>
              ))}
            </Select>
          )}
          
          {filterType === 'harian' && (
            <Select
              value={selectedMonth}
              onValueChange={setSelectedMonth}
              className="w-32"
            >
              {months.map(month => (
                <option key={month.value} value={month.value}>{month.label}</option>
              ))}
            </Select>
          )}
        </div>
      </CardHeader>
      <CardContent className="h-96">
        <ResponsiveContainer width="100%" height="100%">
          <LineChart data={chartData}>
            <CartesianGrid strokeDasharray="3 3" />
            <XAxis 
              dataKey="date" 
              tickFormatter={formatDate}
              angle={-45}
              textAnchor="end"
              height={60}
            />
            <YAxis 
              tickFormatter={formatRupiah}
            />
            <Tooltip
              formatter={(value) => formatRupiah(value)}
              labelFormatter={(label) => formatDate(label)}
            />
            <Legend />
            <Line 
              type="monotone" 
              dataKey="pemasukan" 
              stroke="#22c55e" 
              name="Pemasukan"
              strokeWidth={2}
              dot={{ r: 4 }}
              activeDot={{ r: 8 }}
            />
            <Line 
              type="monotone" 
              dataKey="pengeluaran" 
              stroke="#ef4444" 
              name="Pengeluaran"
              strokeWidth={2}
              dot={{ r: 4 }}
              activeDot={{ r: 8 }}
            />
          </LineChart>
        </ResponsiveContainer>
      </CardContent>
    </Card>
  );
};

export default FinancialChart;