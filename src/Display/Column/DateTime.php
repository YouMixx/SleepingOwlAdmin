<?php

namespace SleepingOwl\Admin\Display\Column;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\DateFormat;

class DateTime extends NamedColumn
{
    use DateFormat;
    /**
     * Datetime format.
     * @var string
     */
    protected $format;

    /**
     * Datetime timezone.
     * @var string
     */
    protected $timezone;

    /**
     * @var string
     */
    protected $view = 'column.datetime';

    /**
     * @param Model $model
     *
     * @return $this
     */
    public function setModel(Model $model)
    {
        parent::setModel($model);
        $this->setHtmlAttribute('data-value', $this->getModelValue());

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $value = $this->getModelValue();

        return parent::toArray() + [
            'value' => $this->getFormatedDate($value),
            'originalValue' => $value,
        ];
    }

    /**
     * @param string $date
     *
     * @return null|string
     */
    protected function getFormatedDate($date)
    {
        if (! $date instanceof Carbon) {
            try {
                $date = Carbon::parse($date);
            } catch (\Exception $e) {
                Log::error('Unable to parse date!', [
                    'format' => $this->getFormat(),
                    'date' => $date,
                    'exception' => $e,
                ]);
                $date = null;
            }
        }

        if (empty($date)) {
            return;
        }

        $date = $date->timezone($this->getTimezone())->format($this->getFormat());

        return $date;
    }
}
